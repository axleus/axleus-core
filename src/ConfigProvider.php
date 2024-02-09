<?php

declare(strict_types=1);

namespace Axleus;

use Axleus\Constants;
use Axleus\CommandBus;
use Laminas\EventManager\EventManager;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\SharedEventManager;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\I18n\Translator\Loader\PhpArray;
use Laminas\Stratigility\Middleware\ErrorHandler;
// use League\Tactician\Middleware;
use League\Tactician\Plugins\NamedCommand\NamedCommandExtractor;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Mezzio\Session\SessionMiddleware;
use TacticianModule\Locator\ClassnameLaminasLocator;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies'          => $this->getDependencies(),
            'middleware_pipeline'   => $this->getPipelineConfig(),
            'routes'                => $this->getRoutes(),
            'tactician'             => $this->getTacticianConfig(),
            'translator'            => $this->getTranslatorConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases'    => [
                EventManagerInterface::class       => EventManager::class,
                'EventManager'                     => EventManager::class,
                SharedEventManagerInterface::class => SharedEventManager::class,
            ],
            'delegators' => [
                Application::class => [
                    ApplicationConfigInjectionDelegator::class,
                ],
            ],
            'factories' => [
                EventManager::class                       => EventManagerFactory::class,
                CommandBus\Event\EventMiddleware::class   => CommandBus\Event\EventMiddlewareFactory::class,
                CommandBus\Listener\CommandBusListener::class => CommandBus\Listener\CommandBusListenerFactory::class,
                SharedEventManager::class                 => fn(): SharedEventManager => new SharedEventManager(),
                Middleware\AjaxRequestMiddleware::class   => Middleware\AjaxRequestMiddlewareFactory::class,
                Middleware\DefaultParamsMiddleware::class => Middleware\DefaultParamsMiddlewareFactory::class,
                Middleware\TranslatorMiddleware::class    => Middleware\TranslatorMiddlewareFactory::class,
            ],
            'invokables' => [
                Handler\PingHandler::class     => Handler\PingHandler::class,
                NamedCommandExtractor::class   => NamedCommandExtractor::class,
                ClassnameLaminasLocator::class => ClassnameLaminasLocator::class
            ],
        ];
    }

    public function getPipelineConfig(): array
    {
        return [
            [// piped first
                'middleware' => ErrorHandler::class,
                'priority'   => Constants::PIPE_PRIORITIES[ErrorHandler::class],
            ],
            [
                'middleware' => ServerUrlMiddleware::class,
                'priority'   => Constants::PIPE_PRIORITIES[ServerUrlMiddleware::class],
            ],
            [
                'middleware' => SessionMiddleware::class,
                'priority'   => Constants::PIPE_PRIORITIES[SessionMiddleware::class],
            ],
            [
                'middleware' => Middleware\TranslatorMiddleware::class,
                'priority'   => Constants::PIPE_PRIORITIES[Middleware\TranslatorMiddleware::class],
            ],
            [// this must be in the pipeline or ajax request fail
                'middleware' => Middleware\AjaxRequestMiddleware::class,
                'priority'   => Constants::PIPE_PRIORITIES[Middleware\AjaxRequestMiddleware::class],
            ],
            /**
             * Middleware that needs to run for all request should be piped here at a
             * priority of 10000 which means they will be piped in the order they are
             * discovered regardless of where they are piped from.
             * Piping order will be determined from the order of their ConfigProviders in
             * config.php
             * priority 10000 is intentionally skipped here for other services to use
             */
            [
                'middleware' => RouteMiddleware::class,
                'priority'   => Constants::PIPE_PRIORITIES[RouteMiddleware::class],
            ],
            [
                'middleware' => [// these are piped together at the same priority so they are piped in the order discovered
                    ImplicitHeadMiddleware::class,
                    ImplicitOptionsMiddleware::class,
                    MethodNotAllowedMiddleware::class,
                ],
                'priority'   => Constants::PIPE_PRIORITIES[ImplicitHeadMiddleware::class],
            ],
            [
                'middleware' => UrlHelperMiddleware::class,
                'priority'   => Constants::PIPE_PRIORITIES[UrlHelperMiddleware::class],
            ],
            [
                'middleware' => Middleware\DefaultParamsMiddleware::class,
                'priority'   => Constants::PIPE_PRIORITIES[Middleware\DefaultParamsMiddleware::class],
            ],
            /**
             * pipe middleware here that needs to introspect the routing result
             * AxleusPluginManagerInterface::ROUTE_RESULT_MIDDLEWARE_PRIORITY = 8000
             */
            [// dispatch at 0
                'middleware' => DispatchMiddleware::class,
                'priority'   => Constants::PIPE_PRIORITIES[DispatchMiddleware::class],
            ],
            [// pipe this VERY late so that everyone has a chance to respond before hitting it
                'middleware' => NotFoundHandler::class,
                'priority'   => Constants::PIPE_PRIORITIES[NotFoundHandler::class],
            ],
        ];
    }

    public function getRoutes(): array
    {
        return [
            [
                'path'            => '/api/ping',
                'name'            => 'api.ping',
                'middleware'      => Handler\PingHandler::class,
                'allowed_methods' => ['GET'],
            ],
        ];
    }

    public function getTacticianConfig(): array
    {
        return [
            'default-extractor' => NamedCommandExtractor::class,
            'middleware' => [
                CommandBus\Event\EventMiddleware::class => 50,
            ],
        ];
    }

    public function getTranslatorConfig(): array
    {
        return [
            'event_manager_enabled'     => true,
            'translation_file_patterns' => [ // This is the only config that is needed for 1 translation per file
                [
                    'type'     => PhpArray::class,
                    'filename' => 'en_US.php',
                    'base_dir' => __DIR__ . '/../language',
                    'pattern'  => '%s.php',
                ],
            ],
        ];
    }
}
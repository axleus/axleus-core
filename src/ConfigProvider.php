<?php

declare(strict_types=1);

namespace Axleus\Core;

use Laminas\EventManager;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Mimmi20\Mezzio\Navigation\NavigationMiddleware;

final class ConfigProvider implements ConfigProviderInterface
{
    public const APP_NAME        = 'app_name';
    public const DATETIME_FORMAT = 'datetime_format';

    public function __invoke(): array
    {
        return [
            static::class              => $this->getAxleusConfig(),
            'dependencies'             => $this->getDependencies(),
            'listeners'                => $this->getListeners(),
            'mezzio-authorization-acl' => $this->getAuthorization(),
            'navigation'               => $this->getNavigation(),
        ];
    }

    public function getAuthorization(): array
    {
        return [
            'resources' => [
                'home',
            ],
            'allow' => [
                'Guest' => [
                    'home',
                ],
            ],
        ];
    }

    public function getAxleusConfig(): array
    {
        return [
            static::APP_NAME        => 'Axleus',
            static::DATETIME_FORMAT => 'Y-m-d H:i:s',
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases'    => [
                EventManager\EventManagerInterface::class       => EventManager\EventManager::class,
                'EventManager'                                  => EventManager\EventManager::class,
                EventManager\SharedEventManagerInterface::class => EventManager\SharedEventManager::class,
                'SharedEventManager'                            => EventManager\SharedEventManager::class,
                NavigationMiddleware::class                     => Middleware\NavigationMiddleware::class,
            ],
            'delegators' => [
                Application::class               => [
                    ApplicationConfigInjectionDelegator::class,
                ],
                EventManager\EventManager::class => [
                    Container\ListenerConfigurationDelegator::class,
                ],
                // \App\Handler\HomePageHandler::class => [
                //     Middleware\AuthorizedHandlerPipelineDelegator::class,
                // ],
            ],
            'factories'  => [
                EventManager\EventManager::class         => Container\EventManagerFactory::class,
                EventManager\SharedEventManager::class   => static fn() => new EventManager\SharedEventManager(),
                Middleware\EventManagerMiddleware::class => Middleware\EventManagerMiddlewareFactory::class,
                Middleware\TemplateMiddleware::class     => Middleware\TemplateMiddlewareFactory::class,
                Middleware\NavigationMiddleware::class              => Middleware\NavigationMiddlewareFactory::class,
            ],
        ];
    }

    public function getListeners(): array
    {
        return [];
    }

    public function getNavigation(): array
    {
        return [
            'default' => [
                [
                    'class' => 'nav-link',
                    'label' => 'Home',
                    'route' => 'home',
                ],
            ],
        ];
    }
}

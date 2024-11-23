<?php

declare(strict_types=1);

namespace Axleus\Core;

use Laminas\EventManager;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'listeners'    => $this->getListeners(),
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
            ],
            'delegators' => [
                Application::class               => [
                    ApplicationConfigInjectionDelegator::class,
                ],
                EventManager\EventManager::class => [
                    Container\ListenerConfigurationDelegator::class,
                ],
            ],
            'factories'  => [
                EventManager\EventManager::class         => Container\EventManagerFactory::class,
                EventManager\SharedEventManager::class   => static fn() => new EventManager\SharedEventManager(),
                Middleware\EventManagerMiddleware::class => Middleware\EventManagerMiddlewareFactory::class,
            ],
        ];
    }

    public function getListeners(): array
    {
        return [];
    }
}

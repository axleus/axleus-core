<?php

declare(strict_types=1);

namespace Axleus\Core;

use Laminas\EventManager;

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
                EventManager\EventManager::class => [
                    Container\ListenerConfigurationDelegator::class,
                ],
            ],
            'invokables' => [
            ],
            'factories'  => [
                EventManager\EventManager::class       => Container\EventManagerFactory::class,
                EventManager\SharedEventManager::class => static fn() => new EventManager\SharedEventManager(),
            ],
        ];
    }

    public function getListeners(): array
    {
        return [];
    }
}

<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\EventManager;
use Psr\Container\ContainerInterface;

final class AuthorizedServiceDelegator
{
    public function __invoke(
        ContainerInterface $container,
        string $serviceName,
        callable $callback
    ) {
        $service = $callback();
        if (! $service instanceof AuthorizedServiceInterface) {
            return $service;
        }
        /** @var EventManager */
        $eventManager = $container->get(EventManagerInterface::class);
        $config       = $container->get('config')['authorization_event'];
        $eventManager->setIdentifiers($config['identifiers'] ?? []);
        /** @var Event\AuthorizationListener */
        $listener = $container->get(Event\AuthorizationListener::class);
        $listener->attach($eventManager);
        $service->setEventManager($eventManager);
        return $service;
    }
}

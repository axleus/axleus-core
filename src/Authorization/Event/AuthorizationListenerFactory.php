<?php

declare(strict_types=1);

namespace Axleus\Authorization\Event;

use Axleus\Authorization\AuthorizationInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

final class AuthorizationListenerFactory
{
    public function __invoke(ContainerInterface $container): AuthorizationListener
    {
        if (! $container->has(AuthorizationInterface::class)) {
            throw new ServiceNotFoundException('Service identifier: ' . AuthorizationInterface::class . ' required but could not be found.');
        }
        return new AuthorizationListener(
            $container->get(AuthorizationInterface::class)
        );
    }
}

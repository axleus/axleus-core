<?php

declare(strict_types=1);

namespace Axleus\Core\Middleware;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;

final class EventManagerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): EventManagerMiddleware
    {
        return new EventManagerMiddleware($container->get(EventManagerInterface::class));
    }
}

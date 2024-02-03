<?php

declare(strict_types=1);

namespace Axleus\DebugBar\Collector;

use Mezzio\Router\RouteCollectorInterface;
use Psr\Container\ContainerInterface;

final class MezzioRouteCollectorFactory
{
    public function __invoke(ContainerInterface $container): MezzioRouteCollector
    {
        return new MezzioRouteCollector($container->get(RouteCollectorInterface::class));
    }
}

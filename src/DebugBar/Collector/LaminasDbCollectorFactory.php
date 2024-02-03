<?php

declare(strict_types=1);

namespace Axleus\DebugBar\Collector;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class LaminasDbCollectorFactory
{
    public function __invoke(ContainerInterface $container): LaminasDbCollector
    {
        return new LaminasDbCollector($container->get(AdapterInterface::class));
    }
}

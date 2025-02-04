<?php

declare(strict_types=1);

namespace Axleus\Core\Container;

use Laminas\EventManager\EventManager;
use Laminas\EventManager\SharedEventManagerInterface;
use Psr\Container\ContainerInterface;

final class EventManagerFactory
{
    public function __invoke(ContainerInterface $container): EventManager
    {
        return new EventManager($container->get(SharedEventManagerInterface::class));
    }
}

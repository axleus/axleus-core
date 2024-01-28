<?php

declare(strict_types=1);

namespace Axleus\CommandBus\Listener;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class CommandBusListenerFactory
{
    public function __invoke(ContainerInterface $container): CommandBusListener
    {
        return new CommandBusListener($container->get(LoggerInterface::class));
    }
}

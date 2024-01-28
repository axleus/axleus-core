<?php

declare(strict_types=1);

namespace Axleus\CommandBus\Event;

use Axleus\CommandBus\Event;
use Axleus\CommandBus\Listener\CommandBusListener;
use Laminas\EventManager\EventManager;
use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;

final class EventMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): Event\EventMiddleware
    {
        /** @var EventManager $em */
        $em = $container->get(EventManagerInterface::class);
        // get the listener
        $listener = $container->get(CommandBusListener::class);
        // attach the listener to its events and pass a eventManager
        $listener->attach($em);
        // create a middleware
        $events = new Event\EventMiddleware();
        // set the eventManager instance
        $events->setEventManager($em);
        // return the middleware to the commandbus
        return $events;
    }
}


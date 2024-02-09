<?php

declare(strict_types=1);

namespace Axleus\CommandBus\Event;

use Axleus\Exception\RecoverableExceptionInterface;
use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerAwareTrait;
use League\Tactician\Middleware;

final class EventMiddleware implements Middleware, EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    public function execute($command, callable $next)
    {
        try {
            $this->getEventManager()->triggerEvent(
                new CommandReceived(
                    $command
                )
            );

            $returnValue = $next($command);

            $this->getEventManager()->triggerEvent(
                new CommandHandled(
                    $command
                )
            );

            return $returnValue;
        // catch may need to only catch RecoverableExceptions
        } catch (RecoverableExceptionInterface $ex) {
            $this->getEventManager()->triggerEvent(
                $event = new CommandFailed($command, $ex)
            );
            if (! $event->isExceptionCaught()) {
                throw $ex;
            }
        }
    }
}

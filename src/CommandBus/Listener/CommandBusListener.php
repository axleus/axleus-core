<?php

declare(strict_types=1);

namespace Axleus\CommandBus\Listener;

use Axleus\CommandBus\Event;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Monolog\Logger;

final class CommandBusListener extends AbstractListenerAggregate
{
    public function __construct(
        private Logger $logger,
    ) {
        $this->logger->withName('command-bus'); // set the channel
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            Event\CommandEventInterface::COMMAND_HANDLED_EVENT,
            [$this, 'commandHandled'],
            $priority
        );
        $this->listeners[] = $events->attach(
            Event\CommandEventInterface::COMMAND_FAILED_EVENT,
            [$this, 'commandFailed'],
            $priority
        );
    }

    public function commandHandled(Event\commandHandled $event)
    {
        /** @var NamedCommand */
        $handled = $event->getCommand();
        $this->logger->info(
            'Handled {command} successfully.', // success message
            [
                'command' => $handled->getCommandName(),
            ]
        );
        $this->logger->close();
    }

    public function commandFailed(Event\CommandFailed $event)
    {
        /** @var NamedCommand */
        $failed = $event->getCommand();
        // use the redefined const or the one from the interface
        $failed->setLogLevel($failed::FAILURE_LOG_LEVEL);
        $ex = $event->getException();
        // get the level so we know which log method to call
        $level = $failed->getLogLevel();

        $this->logger->$level(
            $ex->getMessage(), // failure message
            [
                'command' => $failed->getCommandName(),
            ]
        );
        $this->logger->close();
    }
}

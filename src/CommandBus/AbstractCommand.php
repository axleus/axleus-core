<?php

declare(strict_types=1);

namespace Axleus\CommandBus;

use Psr\Log\LogLevel;

abstract readonly class AbstractCommand implements CommandInterface
{
    /** @var string $logLevel */
    protected string $logLevel;

    public function getCommandName()
    {
        return static::class;
    }

    public function setLogLevel(string $logLevel = LogLevel::DEBUG): void
    {
        $this->logLevel = $logLevel;
    }

    public function getLogLevel(): string
    {
        return $this->logLevel;
    }
}

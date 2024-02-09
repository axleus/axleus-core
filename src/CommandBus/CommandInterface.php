<?php

declare(strict_types=1);

namespace Axleus\CommandBus;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use Psr\Log\LogLevel;

interface CommandInterface extends NamedCommand
{
    public const HANDLED_LOG_LEVEL = LogLevel::INFO;
    public const FAILURE_LOG_LEVEL = LogLevel::NOTICE;

    /** set the LogLevel for this command on failure */
    public function setLogLevel(string $level = LogLevel::DEBUG): void;
    /** get Failure LogLevel */
    public function getLogLevel(): string;
}

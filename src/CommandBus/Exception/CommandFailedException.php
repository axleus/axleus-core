<?php

declare(strict_types=1);

namespace Axleus\CommandBus\Exception;

use Axleus\Exception\RecoverableExceptionInterface;
use Exception;

use function sprintf;

final class CommandFailedException extends Exception implements RecoverableExceptionInterface
{
    /** write setters for level and channel? */
    public static function fromCommand(string $command): self
    {
        return new self(

        );
    }
}

<?php

declare(strict_types=1);

namespace Axleus\CommandBus\Event;

use Axleus\Exception\RecoverableExceptionInterface;
use Exception;
use Laminas\EventManager\Event;

final class CommandFailed extends Event implements CommandEventInterface
{
    use HasCommandTrait;

    /**
     * Checks whether exception is caught
     *
     * @var boolean
     */
    protected $exceptionCaught = false;

    public function __construct(
        protected $target,
        protected Exception|RecoverableExceptionInterface $exception
    ) {
        if ($exception instanceof RecoverableExceptionInterface) {
            $this->catchException();
        }
        parent::__construct(self::COMMAND_FAILED_EVENT);
    }

    /**
     * Returns the exception
     *
     * @return Exception|RecoverableExceptionInterface
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Indicates that exception is caught
     */
    public function catchException()
    {
        $this->exceptionCaught = true;
    }

    /**
     * Checks whether exception is caught
     *
     * @return boolean
     */
    public function isExceptionCaught()
    {
        return $this->exceptionCaught;
    }
}

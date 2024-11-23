<?php

declare(strict_types=1);

namespace Axleus\Core\Middleware;

use Laminas\EventManager\EventManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EventManagerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventManagerInterface $eventManagerInterface
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // If we do not already have it, then attach it
        if (null === $request->getAttribute(EventManagerInterface::class)) {
            $request = $request->withAttribute(EventManagerInterface::class, $this->eventManagerInterface);
        }
        return $handler->handle($request);
    }
}

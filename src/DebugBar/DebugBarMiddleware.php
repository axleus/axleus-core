<?php

declare(strict_types=1);

namespace Axleus\DebugBar;

use DebugBar\DebugBar;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DebugBarMiddleware implements MiddlewareInterface
{
    public function __construct(private DebugBar $debugBar)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(
            DebugBar::class,
            $this->debugBar
        );
        return $handler->handle(
            $request
        );
    }
}

<?php

declare(strict_types=1);

namespace Axleus\Core\Handler;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Trait for Handler usage
 */
trait HandlerTrait
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return match ($request->getMethod()) {
            Http::METHOD_GET    => $this->handleGet($request),
            Http::METHOD_POST   => $this->handlePost($request),
            Http::METHOD_DELETE => $this->handleDelete($request),
            Http::METHOD_PUT    => $this->handlePut($request),
            default             => new EmptyResponse(405),
        };
    }
}

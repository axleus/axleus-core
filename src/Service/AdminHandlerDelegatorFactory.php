<?php

declare(strict_types=1);

namespace Axleus\Service;

use Axleus\Authorization\AuthorizationMiddleware;
use Laminas\Stratigility\MiddlewarePipe;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

final class AdminHandlerDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $serviceName, callable $callback): MiddlewarePipe
    {
        /** @var MiddlewareFactory */
        $factory = $container->get(MiddlewareFactory::class);
        $pipeline = new MiddlewarePipe();
        $pipeline->pipe($factory->prepare(AuthorizationMiddleware::class));
        $pipeline->pipe($factory->prepare($callback()));
        return $pipeline;
    }
}

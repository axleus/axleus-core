<?php

declare(strict_types=1);

namespace Axleus\Core\Middleware;

use Axleus\Core\Middleware\TemplateMiddleware;
use Axleus\Htmx\Middleware\HtmxMiddleware;
use Axleus\Message\Middleware\MessageMiddleware;
use Axleus\UserManager\Middleware\IdentityMiddleware;
use Laminas\Stratigility\MiddlewarePipe;
use Mezzio\Authorization\AuthorizationMiddleware;
use Mezzio\MiddlewareFactory;
use Mezzio\Session\SessionMiddleware;
use Psr\Container\ContainerInterface;

final class AuthorizedHandlerPipelineDelegator
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): MiddlewarePipe
    {
        $factory  = $container->get(MiddlewareFactory::class);
        $pipeline = new MiddlewarePipe();
        $pipeline->pipe($factory->prepare(SessionMiddleware::class));
        $pipeline->pipe($factory->prepare(EventManagerMiddleware::class));
        $pipeline->pipe($factory->prepare(IdentityMiddleware::class));
        $pipeline->pipe($factory->prepare(AuthorizationMiddleware::class));
        $pipeline->pipe($factory->prepare(MessageMiddleware::class));
        $pipeline->pipe($factory->prepare(HtmxMiddleware::class));
        $pipeline->pipe($factory->prepare(TemplateMiddleware::class));

        $pipeline->pipe($factory->prepare($callback()));

        return $pipeline;
    }
}

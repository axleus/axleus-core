<?php

declare(strict_types=1);

namespace Axleus\Core\Middleware;

use Axleus\Authorization\Middleware\AuthorizationMiddleware;
use Axleus\Core\Middleware\TemplateMiddleware;
use Laminas\Stratigility\MiddlewarePipe;
use Mezzio\MiddlewareFactory;
use Mezzio\Session\SessionMiddleware;
//use Mimmi20\Mezzio\GenericAuthorization\AuthorizationMiddleware;
use Mimmi20\Mezzio\Navigation\NavigationMiddleware;
use Psr\Container\ContainerInterface;

use function class_exists;

final class AuthorizedHandlerPipelineDelegator
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): MiddlewarePipe
    {
        $config   = $container->get('config');
        $factory  = $container->get(MiddlewareFactory::class);
        $pipeline = new MiddlewarePipe();
        $pipeline->pipe($factory->prepare(SessionMiddleware::class));
        $pipeline->pipe($factory->prepare(EventManagerMiddleware::class));

        if (class_exists(\Axleus\UserManager\ConfigProvider::class)) {
            $pipeline->pipe($factory->prepare(\Axleus\UserManager\Middleware\IdentityMiddleware::class));
        }

        if (class_exists(AuthorizationMiddleware::class)) {
            $pipeline->pipe($factory->prepare(AuthorizationMiddleware::class));
        }

        // if (class_exists(\Axleus\Log\ConfigProvider::class)) {
        //     $pipeline->pipe($factory->prepare(\Axleus\Log\Middleware\MonologMiddleware::class));
        // }

        if (class_exists(\Axleus\Message\ConfigProvider::class)) {
            $pipeline->pipe($factory->prepare(\Axleus\Message\Middleware\MessageMiddleware::class));
        }

        if (
            class_exists(\Axleus\Htmx\ConfigProvider::class)
            && $config[\Axleus\Htmx\ConfigProvider::class]['enable']
        ) {
            $pipeline->pipe($factory->prepare(\Axleus\Htmx\Middleware\HtmxMiddleware::class));
        }

        $pipeline->pipe($factory->prepare(TemplateMiddleware::class));

        $pipeline->pipe($factory->prepare(NavigationMiddleware::class));
        $pipeline->pipe($factory->prepare($callback()));

        return $pipeline;
    }
}

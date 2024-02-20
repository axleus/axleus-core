<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

use function assert;
use function sprintf;

class AuthorizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): AuthorizationMiddleware
    {
        if (
            ! $container->has(AuthorizationInterface::class)
        ) {
            throw new ServiceNotFoundException(sprintf(
                'Cannot create %s service; dependency %s is missing',
                AuthorizationMiddleware::class,
                AuthorizationInterface::class
            ));
        }

        $authorization = $container->get(AuthorizationInterface::class);
        assert($authorization instanceof AuthorizationInterface);

        return new AuthorizationMiddleware(
            $authorization,
            $container->get(ResponseInterface::class)
        );
    }
}

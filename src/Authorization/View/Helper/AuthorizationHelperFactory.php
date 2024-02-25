<?php

declare(strict_types=1);

namespace Axleus\Authorization\View\Helper;

use Axleus\Authorization\AuthorizationInterface;
use Psr\Container\ContainerInterface;

final class AuthorizationHelperFactory
{
    public function __invoke(ContainerInterface $container): AuthorizationHelper
    {
        return new AuthorizationHelper(
            $container->get(AuthorizationInterface::class)
        );
    }
}

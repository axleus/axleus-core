<?php

declare(strict_types=1);

namespace Axleus\Core;

use Psr\Container\ContainerInterface;

final class ConfigProvider
{
    public function __invoke(ContainerInterface $container): array
    {
        return [];
    }
}

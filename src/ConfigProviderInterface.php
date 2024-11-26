<?php

declare(strict_types=1);

namespace Axleus\Core;

interface ConfigProviderInterface
{
    public function getAxleusConfig(): array;
}

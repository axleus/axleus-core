<?php

declare(strict_types=1);

namespace Axleus\Core;

interface ConfigProviderInterface
{
    public const TARGET_FILE = '';
    public function getAxleusConfig(): array;
}

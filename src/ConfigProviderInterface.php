<?php

declare(strict_types=1);

namespace Axleus\Core;

interface ConfigProviderInterface
{
    public final const AXLEUS_KEY = 'axleus_settings';

    public function getAxleusSettings(): array;
}

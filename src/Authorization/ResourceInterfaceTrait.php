<?php

declare(strict_types=1);

namespace Axleus\Authorization;

trait ResourceInterfaceTrait
{
    /**
     * @psalm-return class-string
     */
    final public function getResourceId(): string
    {
        return static::class;
    }
}

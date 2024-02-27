<?php

declare(strict_types=1);

namespace Axleus\Authorization;

trait ResourceInterfaceTrait
{
    protected ?string $resourceId = null;
    /**
     * @psalm-return class-string
     */
    final public function getResourceId(): string
    {
        $this->resourceId = static::class;
        return $this->resourceId;
    }
}

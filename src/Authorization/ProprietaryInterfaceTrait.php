<?php

declare(strict_types=1);

namespace Axleus\Authorization;

trait ProprietaryInterfaceTrait
{
    public function getOwnerId(): int|string|null
    {
        return $this->ownerId;
    }
}

<?php

declare(strict_types=1);

namespace Axleus\Authorization;

trait ProprietaryInterfaceTrait
{
    protected ?int $ownerId = null;

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }
}

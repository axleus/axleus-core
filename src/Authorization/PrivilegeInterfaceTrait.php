<?php

declare(strict_types=1);

namespace Axleus\Authorization;

trait PrivilegeInterfaceTrait
{
    public function getPrivilegeId(): ?string
    {
        return $this->privilegeId;
    }
}

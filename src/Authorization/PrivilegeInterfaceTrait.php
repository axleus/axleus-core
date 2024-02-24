<?php

declare(strict_types=1);

namespace Axleus\Authorization;

trait PrivilegeInterfaceTrait
{
    private ?string $privilege = null;

    public function setPrivilege(?string $privilege): void
    {
        $this->privilege = $privilege;
    }

    public function getPrivilege(): ?string
    {
        return $this->privilege;
    }
}

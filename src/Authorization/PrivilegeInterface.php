<?php

declare(strict_types=1);

namespace Axleus\Authorization;

interface PrivilegeInterface
{
    public function setPrivilege(?string $privilege): void;
    public function getPrivilege(): ?string;
}

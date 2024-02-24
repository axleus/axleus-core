<?php

declare(strict_types=1);

namespace Axleus\Authorization;

interface PrivilegeInterface
{
    public function setPrivilege(): void;
    public function getPrivilege(): ?string;
}

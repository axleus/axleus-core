<?php

declare(strict_types=1);

namespace Axleus\Authorization;

interface RoleInterface
{
    public function setRoleId(array|string|null $roleId): void;
}

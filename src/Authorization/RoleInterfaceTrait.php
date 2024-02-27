<?php

declare(strict_types=1);

namespace Axleus\Authorization;

trait RoleInterfaceTrait
{
    /**
     *
     * @var array<int|string, string>|string|null $roleId
     */
    protected array|string|null $roleId = null;

    public function setRoleId(array|string|null $roleId): void
    {
        $this->roleId = $roleId;
    }

    /**
     *
     * @return array<int|string, string>|string|null
     */
    public function getRoleId(): array|string|null
    {
        return $this->roleId;
    }
}

<?php

declare(strict_types=1);

namespace Axleus\Authorization\View\Helper;

use Axleus\Authorization\AuthorizationInterface;
use Axleus\Authorization\PrivilegeInterface;
use Axleus\Authorization\RoleInterface;
use Laminas\Permissions\Acl\Role\RoleInterface as LaminasRoleInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\View\Helper\AbstractHelper;

final class AuthorizationHelper extends AbstractHelper
{
    public function __construct(
        private AuthorizationInterface $authorization
    ) {
    }

    public function __invoke(
        LaminasRoleInterface|RoleInterface $role = null,
        ResourceInterface|string|null      $resource  = null,
        PrivilegeInterface|string|null     $privilege = null,
    ) {
        return $this->authorization->isAllowed($role, $resource, $privilege);
    }
}

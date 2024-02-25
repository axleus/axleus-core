<?php

declare(strict_types=1);

namespace Axleus\Authorization\View\Helper;

use Axleus\Authorization\AuthorizationInterface;
use Axleus\Authorization\PrivilegeInterface;
use Laminas\Permissions\Acl;
use Laminas\View\Helper\AbstractHelper;

final class AuthorizationHelper extends AbstractHelper
{
    public function __construct(
        private AuthorizationInterface $authorization
    ) {
    }
    public function __invoke(
        Acl\Role\RoleInterface|array|string|null   $role      = null,
        Acl\Resource\ResourceInterface|string|null $resource  = null,
        PrivilegeInterface|string|null             $privilege = null,
    ) {
        return $this->authorization->isAllowed($role, $resource, $privilege);
    }
}

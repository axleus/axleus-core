<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\Permissions\Acl\Role\RoleInterface as LaminasRoleInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Psr\Http\Message\ServerRequestInterface;

interface AuthorizationInterface
{
    public final const ADMIN_ROLE = 'Administrator';
    public final const GUEST_ROLE = 'Guest';

    public function isAllowed(
        LaminasRoleInterface|RoleInterface|array|string|null $roleId      = null,
        ResourceInterface|string|null                        $resourceId  = null,
        PrivilegeInterface|string|null                       $privilegeId = null
    ): bool;

    public function authorize(AuthorizationEvent $event): bool;
}

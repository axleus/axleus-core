<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\Permissions\Acl;
use Psr\Http\Message\ServerRequestInterface;

interface AuthorizationInterface
{
    public final const ADMIN_ROLE = 'Administrator';
    public final const GUEST_ROLE = 'Guest';

    public function isAllowed(
        Acl\Role\RoleInterface|array|string|null   $role      = null,
        Acl\Resource\ResourceInterface|string|null $resource  = null,
        PrivilegeInterface|string|null             $privilege = null,
        ?ServerRequestInterface                    $request   = null
    ): bool;

    public function authorize(AuthorizationEvent $event): bool;
}

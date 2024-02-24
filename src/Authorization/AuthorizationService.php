<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\Permissions\Acl\Acl;
use Psr\Http\Message\ServerRequestInterface;

final class AuthorizationService implements AuthorizationInterface
{
    public function __construct(
        private Acl $acl
    ) {
    }

    public function authorize(AuthorizationEvent $event): bool
    {
        [$role, $resource, $privilege] = $event->getParams();
        return $this->isAllowed($role, $resource, $privilege);
    }

    public function isAllowed(
        $role      = null,
        $resource  = null,
        $privilege = null,
        ?ServerRequestInterface $request = null
    ): bool {
        // Laminas\Permissions\Acl does not support this but we do.
        if ($privilege instanceof PrivilegeInterface) {
            $privilege = $privilege->getPrivilege();
        }
        return $this->acl->isAllowed($role, $resource, $privilege);
    }
}

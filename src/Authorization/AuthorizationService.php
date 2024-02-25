<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\Permissions\Acl;
use Psr\Http\Message\ServerRequestInterface;

use function is_array;

final class AuthorizationService implements AuthorizationInterface
{
    public function __construct(
        private Acl\Acl $acl
    ) {
    }

    public function authorize(AuthorizationEvent $event): bool
    {
        return $this->isAllowed(
            $event->getParam('role'),
            $event->getParam('resource'),
            $event->getParam('privilege')
        );
    }

    public function isAllowed(
        Acl\Role\RoleInterface|array|string|null   $role      = null,
        Acl\Resource\ResourceInterface|string|null $resource  = null,
        PrivilegeInterface|string|null             $privilege = null,
        ?ServerRequestInterface                    $request   = null
    ): bool {
        // Laminas\Permissions\Acl does not support this but we do.
        if ($privilege instanceof PrivilegeInterface) {
            $privilege = $privilege->getPrivilege();
        }
        $check = false;
        // Laminas\Permissions\Acl does not support multiple roles, but we do.
        if (! is_array($role)) {
            $check = $this->acl->isAllowed($role, $resource, $privilege);
        } else {
            foreach ($role as $roleCheck) {
                if ($this->acl->isAllowed($roleCheck, $resource, $privilege)) {
                    $check = true;
                }
            }
        }
        return $check;
    }
}

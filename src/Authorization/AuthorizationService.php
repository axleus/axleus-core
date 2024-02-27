<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface as LaminasRoleInterface;
use Mezzio\Authentication\UserInterface;

final class AuthorizationService implements AuthorizationInterface
{
    public function __construct(
        private Acl $acl
    ) {
    }

    public function authorize(AuthorizationEvent $event): bool
    {
        $target = $event->getTarget();
        return $this->isAllowed(
            $event->getParam('roleId') ?? $target?->getRoleId(),
            $event->getParam('resourceId') ?? $target?->getResourceId(),
            $event->getParam('privilegeId') ?? $target?->getPrivilegeId()
        );
    }

    public function isAllowed(
        LaminasRoleInterface|RoleInterface|array|string|null $roleId      = null,
        ResourceInterface|string|null                        $resourceId  = null,
        PrivilegeInterface|string|null                       $privilegeId = null
    ): bool {

        // Laminas\Permissions\Acl does not support this but we do.
        if ($privilegeId instanceof PrivilegeInterface) {
            $privilegeId = $privilegeId->getPrivilegeId();
        }

        // if we are passed a string run it now
        if (is_string($roleId)) {
            return $this->acl->isAllowed($roleId, $resourceId, $privilegeId);
        }

        $check = false;
        $resetRoles = false;

        if ($roleId instanceof LaminasRoleInterface) {
            /** @var array */
            $roles = $roleId->getRoleId();
        }

        // support mezzio multi-role implementation
        if ((is_array($roleId) || $roleId instanceof UserInterface) && $roleId instanceof RoleInterface) {
            $resetRoles = true;
        }

        if (is_array($roles)) {
            foreach ($roles as $roleCheck) {
                if ($resetRoles) {
                    $roleId->setRoleId($roleCheck);
                }
                if ($this->acl->isAllowed($roleId, $resourceId, $privilegeId)) {
                    $check = true;
                    break;
                }
            }
        }

        if ($roleId instanceof RoleInterface) {
            // reset roleId to an array
            $roleId->setRoleId($roles);
        }

        return $check;
    }
}

<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\Permissions\Acl\Role\RoleInterface as LaminasRoleInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\EventManager\EventManagerAwareTrait;
use Mezzio\Authentication\UserInterface;

trait AuthorizedServiceTrait
{
    use EventManagerAwareTrait;
    use PrivilegeInterfaceTrait;
    use ProprietaryInterfaceTrait;
    use ResourceInterfaceTrait;
    use RoleInterfaceTrait;

    protected function isAllowed(
        LaminasRoleInterface|RoleInterface|array|string|null $roleId      = null,
        ResourceInterface|string|null                        $resourceId  = null,
        PrivilegeInterface|string|null                       $privilegeId = null,
    ): bool {
        $event = new AuthorizationEvent(
            AuthorizationEvent::AUTHORIZATION_EVENT,
            $this
        );
        // pass these and prefer event params over calls to target since they were passed at call time
        $event->setRoleId($roleId);
        $event->setResourceId($resourceId);
        $event->setPrivilegeId($privilegeId);
        $eventManager = $this->getEventManager();
        $result = $eventManager->triggerEvent(
            $event,
        );
        return $result->last();
    }
}

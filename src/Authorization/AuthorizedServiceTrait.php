<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\Permissions\Acl;
use Laminas\EventManager\EventManagerAwareTrait;
use Mezzio\Authentication\UserInterface;
use Psr\Http\Message\ServerRequestInterface;

trait AuthorizedServiceTrait
{
    use EventManagerAwareTrait;

    protected function isAllowed(
        Acl\Role\RoleInterface|array|string|null   $role      = null,
        Acl\Resource\ResourceInterface|string|null $resource  = null,
        PrivilegeInterface|string|null             $privilege = null,
        ?ServerRequestInterface                    $request   = null
    ): bool {
        $event = new AuthorizationEvent(
            AuthorizationEvent::AUTHORIZATION_EVENT,
        );

        $user = $request?->getAttribute(UserInterface::class);
        // prefer the role passed at call time
        $role = $role ?? $user?->getRoles();
        if ($role !== null) {
            $request = null;
        }
        $event->setRole($role);
        if ($resource === null && $this instanceof AdminResourceInterface) {
            $resource = $this;
        } else {
            $resource = static::class;
        }
        $event->setResource($resource);
        if ($privilege === null && $this instanceof PrivilegeInterface) {
            $privilege = $this;
        }
        $event->setPrivilege($privilege);
        $eventManager = $this->getEventManager();
        $result = $eventManager->triggerEvent(
            $event
        );
        return $result->last();
    }
}

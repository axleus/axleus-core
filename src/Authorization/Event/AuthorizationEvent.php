<?php

declare(strict_types=1);

namespace Axleus\Authorization\Event;

use Axleus\Authorization\PrivilegeInterface;
use Laminas\EventManager\Event;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthorizationEvent extends Event
{
    public final const AUTHORIZATION_EVENT = 'authorize';

    public function setPrivilegeId(PrivilegeInterface|string|null $privilege): void
    {
        $this->setParam('privilegeId', $privilege);
    }

    public function getPrivilegeId(): PrivilegeInterface|string|Null
    {
        return $this->getParam('privilegeId');
    }

    public function setResourceId(ResourceInterface|string|null $resourceId): void
    {
        $this->setParam('resourceId', $resourceId);
    }

    public function getResourceId(): ResourceInterface|string|null
    {
        return $this->getParam('resourceId');
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->setParam('request', $request);
    }

    public function getRequest(): ServerRequestInterface|null
    {
        return $this->getParam('request');
    }

    public function setRoleId(RoleInterface|array|string|null $role): void
    {
        $this->setParam('roleId', $role);
    }

    public function getRoleId(): RoleInterface|array|string|null
    {
        return $this->getParam('roleId');
    }
}

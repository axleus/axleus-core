<?php

declare(strict_types=1);

namespace Axleus\Authorization\Event;

use Axleus\Authorization\PrivilegeInterface;
use Laminas\EventManager\Event;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;
use phpDocumentor\Reflection\Types\Null_;
use Psr\Http\Message\ServerRequestInterface;

final class AuthorizationEvent extends Event
{
    public final const AUTHORIZATION_EVENT = 'authorize';

    public function setPrivilege(PrivilegeInterface|string $privilege): void
    {
        $this->setParam('privilege', $privilege);
    }

    public function getPrivilege(): PrivilegeInterface|string|Null
    {
        return $this->getParam('privilege');
    }

    public function setResource(ResourceInterface|string $resourceId): void
    {
        $this->setParam('resource', $resourceId);
    }

    public function getResource(): ResourceInterface|string|null
    {
        return $this->getParam('resource');
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->setParam('request', $request);
    }

    public function getRequest(): ServerRequestInterface|null
    {
        return $this->getParam('request');
    }

    public function setRole(RoleInterface|string $role): void
    {
        $this->setParam('role', $role);
    }

    public function getRole(): RoleInterface|string
    {
        return $this->getParam('role');
    }
}

<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Laminas\Permissions\Acl\Acl;

use Psr\Http\Message\ServerRequestInterface;

final class AuthorizationService implements AuthorizationInterface
{
    public function __construct(
        private Acl $acl
    ) {
    }

    public function isAllowed(
        $role = null,
        $resource = null,
        $privilege = null,
        ?ServerRequestInterface $request = null
    ): bool {
        return $this->acl->isAllowed($role, $resource, $privilege);
    }
}

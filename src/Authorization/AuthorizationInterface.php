<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Psr\Http\Message\ServerRequestInterface;

interface AuthorizationInterface
{
    public final const ADMIN_ROLE = 'Administrator';
    public final const GUEST_ROLE = 'Guest';

    public function isAllowed(
        $role = null,
        $resource = null,
        $privilege = null,
        ?ServerRequestInterface $request = null
        ): bool;
}

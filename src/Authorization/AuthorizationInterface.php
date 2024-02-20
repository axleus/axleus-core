<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Psr\Http\Message\ServerRequestInterface;

interface AuthorizationInterface
{
    public function isAllowed(
        $role = null,
        $resource = null,
        $privilege = null,
        ?ServerRequestInterface $request = null
        ): bool;
}

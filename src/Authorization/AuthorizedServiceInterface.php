<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\Permissions\Acl\ProprietaryInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface as LaminasRoleInterface;

interface AuthorizedServiceInterface extends
    RoleInterface,
    EventManagerAwareInterface,
    ProprietaryInterface,
    ResourceInterface,
    LaminasRoleInterface
{
}

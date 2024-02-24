<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\EventManager\EventManagerAwareTrait;

trait AuthorizedServiceTrait
{
    use EventManagerAwareTrait;

}

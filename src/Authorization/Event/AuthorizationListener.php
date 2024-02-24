<?php

declare(strict_types=1);

namespace Axleus\Authorization\Event;

use Axleus\Authorization\AuthorizationAwareInterface;
use Axleus\Authorization\AuthorizationService;
use Axleus\Authorization\Event\AuthorizationEvent;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;

final class AuthorizationListener extends AbstractListenerAggregate
{
    public function __construct(
        private AuthorizationService $authorizationService
    ) {
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();
        $this->listeners[] = $sharedManager->attach(
            AuthorizationAwareInterface::class,
            AuthorizationEvent::AUTHORIZATION_EVENT,
            [$this, 'authorize'],
            $priority
        );
    }

    public function authorize(AuthorizationEvent $event): bool
    {
        return $this->authorizationService->authorize($event);
    }
}

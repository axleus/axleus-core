<?php

declare(strict_types=1);

namespace Axleus\Authorization;

interface AuthorizedHandlerInterface extends AdminResourceInterface, PrivilegeInterface
{
    public function setAuthorizationService(AuthorizationInterface $authorizationService): void;
    public function getAuthorizationService(): ?AuthorizationInterface;
}

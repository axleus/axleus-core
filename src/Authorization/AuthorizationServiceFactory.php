<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use DomainException;
use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Exception\ExceptionInterface as AclExceptionInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;
use Psr\Container\ContainerInterface;

use function array_reverse;

final class AuthorizationServiceFactory
{
    /**
     * @throws DomainException
     */
    public function __invoke(ContainerInterface $container): AuthorizationInterface
    {
        $config = $container->get('config')['authorization'] ?? null;
        if (null === $config) {
            throw new DomainException(
                'No authorization config provided'
            );
        }
        if (! isset($config['roles'])) {
            throw new DomainException(
                'No authorization roles configured for AuthorizationService'
            );
        }
        if (! isset($config['resources'])) {
            throw new DomainException(
                'No authorization resources configured for AuthorizationService'
            );
        }

        $acl = new Acl();

        $this->injectRoles($acl, $config['roles']);
        $this->injectResources($acl, $config['resources']);
        $this->injectPermissions($acl, $config['allow'] ?? [], 'allow');
        $this->injectPermissions($acl, $config['deny'] ?? [], 'deny');

        return new AuthorizationService($acl);
    }

    /**
     * @throws DomainException
     * @param array<string, list<RoleInterface|string>> $roles
     */
    private function injectRoles(Acl $acl, array $roles): void
    {
        $roles = array_reverse($roles);
        foreach ($roles as $role => $parents) {
            if (! $acl->hasRole($role)) {
                try {
                    $acl->addRole($role, $parents);
                } catch (AclExceptionInterface $e) {
                    throw new DomainException($e->getMessage(), $e->getCode(), $e);
                }
            }
        }
    }

    /**
     * @throws DomainException
     * @param list<ResourceInterface|string> $resources
     */
    private function injectResources(Acl $acl, array $resources): void
    {
        foreach ($resources as $resource) {
            try {
                $acl->addResource($resource);
            } catch (AclExceptionInterface $e) {
                throw new DomainException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }

    /**
     * @throws DomainException
     * @param array<string, ResourceInterface|string|list<ResourceInterface|string|array>> $permissions
     */
    private function injectPermissions(Acl $acl, array $permissions, string $type): void
    {
        if (! in_array($type, ['allow', 'deny'], true)) {
            throw new DomainException(sprintf(
                'Invalid permission type "%s" provided in configuration; must be one of "allow" or "deny"',
                $type
            ));
        }

        foreach ($permissions as $role => $resources) {
            foreach ($resources as $resource => $privileges) {
                try {

                    if ($type === 'allow') {
                        $acl->allow($role, $resource, $privileges['privileges']);
                    } else {
                        $acl->deny($role, $resource, $privileges['privileges']);
                    }

                    if (isset($privileges['assertions']) && is_array($privileges['assertions'])) {
                        foreach ($privileges['assertions'] as $assertion) {
                            if ($type === 'allow') {
                                $acl->allow($role, $resource, $assertion['privileges'], new $assertion['assert']());
                            } else {
                                $acl->deny($role, $resource, $assertion['privileges'], new $assertion['assert']());
                            }
                        }
                    }

                } catch (AclExceptionInterface $e) {
                    throw new DomainException($e->getMessage(), $e->getCode(), $e);
                }
            }
            // Administrator gets all privileges
            if ($role === AuthorizationInterface::ADMIN_ROLE) {
                $acl->allow(AuthorizationInterface::ADMIN_ROLE);
            }
        }
    }
}

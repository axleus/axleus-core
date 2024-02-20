<?php

declare(strict_types=1);

namespace Axleus\Authorization;

use Mezzio\Authentication\UserInterface;
use Mezzio\Router\Route;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthorizationMiddleware implements MiddlewareInterface
{
    /** @var callable */
    private $responseFactory;

    public function __construct(private AuthorizationInterface $authorization, callable $responseFactory)
    {
        // Ensures type safety of the composed factory
        $this->responseFactory = static fn(): ResponseInterface => $responseFactory();
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute(UserInterface::class, false);
        if (! $user instanceof UserInterface) {
            return ($this->responseFactory)()->withStatus(401);
        }

        $routeName   = null;
        $context     = null;
        $routeResult = $request?->getAttribute(RouteResult::class, null);
        // if its not null check it
        if ($routeResult?->isFailure()) {
            // if its a failure then return true since we need to know without a 403
            return true;
        } else {
            /** @var Route|null */
            $routeName = $routeResult?->getMatchedRouteName();
            $context   = $request?->getAttribute(AuthorizationContextInterface::class);
        }
        if (is_array($context) && in_array($routeName, $context)) {
            foreach ($user->getRoles() as $role) {
                if (
                    $this->authorization->isAllowed(
                        $role,
                        $context['resource'],
                        $context['privilege']
                    )
                ) {
                    return $handler->handle($request);
                }
            }
        }
        return ($this->responseFactory)()->withStatus(403);
    }
}

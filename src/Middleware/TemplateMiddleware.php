<?php

declare(strict_types=1);

namespace Axleus\Core\Middleware;

use Mezzio\Authentication\UserInterface;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TemplateMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TemplateRendererInterface $template,
        private array $config,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class, null);
        $routeName   = $routeResult?->getMatchedRouteName();
        $user        = $request->getAttribute(UserInterface::class);
        // If we have a user then assign it to all templates, this does not assign it to partials
        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'user',
            $user
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'request',
            $request
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'currentRoute',
            $routeName
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'config',
            $this->config
        );

        return $handler->handle($request);
    }
}

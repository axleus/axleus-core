<?php

declare(strict_types=1);

namespace Axleus\Middleware;

use Mezzio\Authentication\UserInterface;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessages;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DefaultParamsMiddleware implements MiddlewareInterface
{
    /**
     * @param TemplateRendererInterface $template
     * @param UrlHelper $urlHelper
     * @return void
     */
    public function __construct(
        private TemplateRendererInterface $template,
        private UrlHelper $urlHelper,
        private array $config
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'debug',
            $this->config['debug']
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'currentUser',
            $request->getAttribute(UserInterface::class)
        );

        $routeResult = $request->getAttribute(RouteResult::class, null);
        /** @var non-empty-string|null */
        $matchedRouteName = $routeResult?->getMatchedRouteName();
        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'matchedRouteName',
            $matchedRouteName
        );

        if ($routeResult?->isSuccess()) {
            /** @var array<string, mixed> */
            $params = $request->getQueryParams();
            /**
             * @psalm-suppress PossiblyFalseArgument
             * as we just checked if routing was successful
             */
            $this->template->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'url',
                $this->urlHelper->generate(
                    $matchedRouteName,
                    $routeResult->getMatchedParams(),
                    $params
                )
            );
        }
        /** @var FlashMessages|null */
        $flashMessages = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE, false);
        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'systemMessages',
            $flashMessages ? $flashMessages->getFlashes() : []
        );

        return $handler->handle($request);
    }
}

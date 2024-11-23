<?php

declare(strict_types=1);

namespace Axleus\Core\Middleware;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;
/**
 * Middleware that detects "standard" ajaxed request determined by the most commonly used request header
 * There is no need to use this if the HtmxMiddelware is in use.
 * @package Axleus\Core\Middleware
 */
class AjaxRequestMiddleware implements MiddlewareInterface
{
    public const HTML_CONTENT_TYPE = 'text/html';

    public function __construct(
        private TemplateRendererInterface $renderer,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! $request->hasHeader('X-Requested-With')) {
            // if we do not have this bail early
            return $handler->handle($request->withAttribute('isAjax', false));
        }

        if (in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true)) {
            // for ajax do not render the layout again
            $this->renderer->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'layout',
                false
            );
        }

        return $handler->handle($request->withAttribute('isAjax', true));
    }
}

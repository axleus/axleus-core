<?php

declare(strict_types=1);

namespace Axleus;

use Laminas\Stratigility\Middleware\ErrorHandler;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Mezzio\Session\SessionMiddleware;

interface Constants
{

    public const PIPE_PRIORITIES = [
        ErrorHandler::class                               => 10700,
        ServerUrlMiddleware::class                        => 10600,
        SessionMiddleware::class                          => 10500,
        Middleware\TranslatorMiddleware::class            => 10400,
        Middleware\AjaxRequestMiddleware::class           => 10300,
        \UserManager\Middleware\IdentityMiddleware::class => 10200,
        \Axleus\Log\MonologMiddleware::class              => 10100,
        // PluginInterface::PLUGIN_PIPED_MIDDLEWARE_PRIORITY => 10000
        RouteMiddleware::class                            => 9900,
        ImplicitHeadMiddleware::class                     => 9800,
        ImplicitOptionsMiddleware::class                  => 9800,
        MethodNotAllowedMiddleware::class                 => 9800,
        UrlHelperMiddleware::class                        => 9700,
        Middleware\DefaultParamsMiddleware::class         => 9600,
        // PluginInterface::ROUTE_RESULT_MIDDLEWARE_PRIORITY => 8000
        DispatchMiddleware::class                         => 0,
        NotFoundHandler::class                            => -10000,
    ];
}

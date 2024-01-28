<?php

declare(strict_types=1);

namespace Axleus\Middleware;

use Laminas\EventManager\EventManagerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerInterface;

final class TranslatorMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): TranslatorMiddleware
    {
        return new TranslatorMiddleware(
            $container->get(EventManagerInterface::class),
            $container->get(TranslatorInterface::class),
            $container->get(HelperPluginManager::class)
        );
    }
}

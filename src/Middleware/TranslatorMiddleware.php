<?php

declare(strict_types=1);

namespace Axleus\Middleware;

use Laminas\EventManager\EventManagerInterface;
use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\I18n\ConfigProvider;
use Laminas\View\HelperPluginManager;
use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;
use function str_contains;

class TranslatorMiddleware implements MiddlewareInterface
{
    private const SERVER_KEY = 'HTTP_ACCEPT_LANGUAGE';
    private const FALLBACK_LANG = 'en_US';

    public function __construct(
        private EventManagerInterface $eventManager,
        private TranslatorInterface|Translator $translator,
        private HelperPluginManager $pm,
        private ?array $langSettings = null
    ) {
        $this->translator->setEventManager($this->eventManager);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $server = $request->getServerParams();
        $locale = Locale::acceptFromHttp($server[self::SERVER_KEY] ?? self::FALLBACK_LANG);
        if ($locale !== null) {
            $this->translator->setlocale($locale);
            $this->translator->setFallbackLocale(self::FALLBACK_LANG);
        }
        $this->pm->configure((new ConfigProvider())->getViewHelperConfig());

        // if (! $request->hasHeader('X-Requested-With')) {
        //     // if we do not have this bail early
        //     return $handler->handle($request->withAttribute('isAjax', false));
        // }

        // if (in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true)) {
        //     // for ajax do not render the layout again
        //     $this->template->addDefaultParam(
        //         TemplateRendererInterface::TEMPLATE_ALL,
        //         'layout',
        //         false
        //     );
        // }

        return $handler->handle($request->withAttribute('translator', $this->translator));
    }
}

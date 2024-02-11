<?php

declare(strict_types=1);

namespace Axleus\Service\Delegator;

use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

use function gettype;

final class TranslatorAwareInterfaceDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): TranslatorAwareInterface
    {
        $service = $callback();
        if (! $container->has(TranslatorInterface::class)) {
            // if we do not have a translator, return the service as is.
            return $service;
        }
        Assert::isInstanceOf(
            $service,
            TranslatorAwareInterface::class,
            'Service must be an instance of '
            . TranslatorAwareInterface::class
            . ' to be delegated, received: ' . gettype($service)
        );
        // set the translator
        $service->setTranslator($container->get(TranslatorInterface::class));
        return $service;
    }
}

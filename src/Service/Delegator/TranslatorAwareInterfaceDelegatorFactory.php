<?php

declare(strict_types=1);

namespace Axleus\Service\Delegator;

use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

use function class_uses;
use function in_array;
use function gettype;

final class TranslatorAwareInterfaceDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback)
    {
        $service = $callback();
        if (! $container->has(TranslatorInterface::class)) {
            // if we do not have a translator, return the service as is.
            return $service;
        }

        // Only decorate the service if either of these are true
        if (
            ($service instanceof TranslatorAwareInterface
            || in_array(TranslatorAwareTrait::class, class_uses($service)))
        ) {
            $service->setTranslator($container->get(TranslatorInterface::class));
        }

        return $service;
    }
}

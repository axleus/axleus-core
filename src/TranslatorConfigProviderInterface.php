<?php

declare(strict_types=1);

namespace Axleus;

interface TranslatorConfigProviderInterface
{
    public function getTranslatorConfig(): array;
}

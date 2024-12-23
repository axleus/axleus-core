<?php

declare(strict_types=1);

namespace Axleus\Core\Stdlib;

trait EnumTrait
{
    use EnumToArrayTrait;

    public static function noramalize(string $value): string
    {
        return self::isValid($value) ? $value : self::fromValue($value)->value;
    }
}

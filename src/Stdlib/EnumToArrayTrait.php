<?php

declare(strict_types=1);

namespace Axleus\Core\Stdlib;

use function array_column;

trait EnumToArrayTrait
{
    public static function names(?callable $treatment = null): array
    {
        $names = array_column(self::cases(), 'name');
        if ($treatment === null) {
            return $names;
        }
        $normalized = [];
        foreach ($names as $name) {
            $normalized[] = $treatment($name);
        }
        return $normalized;
    }

    public static function values(?callable $treatment = null): array
    {
        $values = array_column(self::cases(), 'value');
        if ($treatment === null) {
            return $values;
        }
        $normalized = [];
        foreach ($values as $value) {
            $normalized[] = $treatment($value);
        }
        return $normalized;
    }

    public static function toArray(
        bool $normalize = false,
        ?callable $nameTreatment = null,
        ?callable $valueTreatment = null,
    ): array {
        if (empty(self::values())) {
            return self::names();
        }
        if (empty(self::names())) {
            return self::values();
        }
        if ($normalize) {
            return array_combine(self::names($nameTreatment), self::values($valueTreatment));
        }
        return array_column(self::cases(), 'name', 'value');
    }
}

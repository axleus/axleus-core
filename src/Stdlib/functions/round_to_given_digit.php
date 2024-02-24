<?php

declare(strict_types=1);

namespace Axleus\Stdlib;

function roundToGivenDigit($number, $digit): float
{
    $multiplier = 1;
    while ($number < 0.1) {
        $number *= 10;
        $multiplier /= 10;
    }
    while ($number >= 1) {
        $number /= 10;
        $multiplier *= 10;
    }
    return round($number, $digit) * $multiplier;
}
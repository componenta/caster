<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Rounds a numeric value to the specified precision.
 *
 * Usage: "round" (0 decimals) or "round:2"
 *
 * ""3.14159 -> 3.14
 */
final class Round implements CasterInterface
{
    private(set) string $name = 'round';

    public function __construct(
        private readonly int $precision = 0,
    ) {}

    public function cast(mixed $value): int|float
    {
        if (!is_numeric($value)) {
            throw new CasterException($value, $this->name);
        }

        return round((float) $value, $this->precision);
    }
}

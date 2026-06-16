<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Clamps a numeric value to a min/max range.
 *
 * Usage: "clamp:0,100" -> 150 -> 100, -5 -> 0
 */
final class Clamp implements CasterInterface
{
    private(set) string $name = 'clamp';

    public function __construct(
        private readonly float $min,
        private readonly float $max,
    ) {}

    public function cast(mixed $value): int|float
    {
        if (!is_numeric($value)) {
            throw new CasterException($value, $this->name);
        }

        $float = (float) $value;

        return max($this->min, min($this->max, $float));
    }
}

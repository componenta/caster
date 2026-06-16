<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Returns the absolute value of a number.
 *
 * Strict: null is rejected. Use `?abs` if the source is nullable.
 */
final class Abs implements CasterInterface
{
    private(set) string $name = 'abs';

    public function cast(mixed $value): int|float
    {
        if (!is_numeric($value)) {
            throw new CasterException($value, $this->name);
        }

        return abs(is_int($value) ? $value : (float) $value);
    }
}

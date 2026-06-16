<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Casts values to float.
 *
 * Supported input types:
 * - float: returned as-is
 * - int, string, bool: cast via (float)
 */
final class Decimal implements CasterInterface
{
    private(set) string $name = 'float';

    public function cast(mixed $value): float
    {
        if (is_float($value)) {
            return $value;
        }

        if (is_int($value) || is_string($value) || is_bool($value)) {
            return (float) $value;
        }

        throw new CasterException($value, $this->name);
    }
}

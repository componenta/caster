<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Casts values to integer.
 *
 * Supported input types:
 * - int: returned as-is
 * - float, string, bool: cast via (int)
 */
final class Integer implements CasterInterface
{
    private(set) string $name = 'int';

    public function cast(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_float($value) || is_string($value) || is_bool($value)) {
            return (int) $value;
        }

        throw new CasterException($value, $this->name);
    }
}

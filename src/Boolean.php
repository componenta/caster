<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Casts values to boolean.
 *
 * Supported input types:
 * - bool: returned as-is
 * - int: 0 -> false, non-zero -> true
 * - string: "1", "true", "yes", "on" -> true; "0", "false", "no", "off", "" -> false
 */
final class Boolean implements CasterInterface
{
    private(set) string $name = 'bool';

    public function cast(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return $value !== 0;
        }

        if (is_string($value)) {
            return match (strtolower($value)) {
                '1', 'true', 'yes', 'on' => true,
                '0', 'false', 'no', 'off', '' => false,
                default => throw new CasterException($value, $this->name),
            };
        }

        throw new CasterException($value, $this->name);
    }
}

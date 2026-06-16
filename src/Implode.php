<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Joins an array into a string with separator.
 *
 * Usage: "implode" (comma) or "implode:;"
 *
 * ""['a','b'] -> "a,b"
 */
final class Implode implements CasterInterface
{
    private(set) string $name = 'implode';

    public function __construct(
        private readonly string $separator = ',',
    ) {}

    public function cast(mixed $value): string
    {
        if (!is_array($value)) {
            throw new CasterException($value, $this->name);
        }

        return implode($this->separator, $value);
    }
}

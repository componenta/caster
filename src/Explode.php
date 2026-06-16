<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Splits a string by separator without trimming.
 *
 * Usage: "explode" (comma) or "explode:;" (semicolon)
 *
 * """a;b;c" -> ['a','b','c']
 */
final class Explode implements CasterInterface
{
    private(set) string $name = 'explode';

    public function __construct(
        private readonly string $separator = ',',
    ) {}

    public function cast(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        if ($value === '') {
            return [];
        }

        return explode($this->separator, $value);
    }
}

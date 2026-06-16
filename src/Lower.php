<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Converts string to lowercase (multibyte-safe).
 *
 * """Hello" -> "hello"
 */
final class Lower implements CasterInterface
{
    private(set) string $name = 'lower';

    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        return mb_strtolower($value);
    }
}

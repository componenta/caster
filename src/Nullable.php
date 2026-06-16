<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Converts empty-ish values to null.
 *
 * null -> null, "" -> null, "null" -> null, "0" -> "0" (kept)
 */
final class Nullable implements CasterInterface
{
    private(set) string $name = 'nullable';

    public function cast(mixed $value): mixed
    {
        if ($value === null || $value === '' || $value === 'null') {
            return null;
        }

        return $value;
    }
}

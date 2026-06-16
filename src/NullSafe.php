<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Wraps another caster so that `null` passes through unchanged. Used by
 * the `?<name>` syntax in {@see CasterProvider}: e.g. `?datetime` means
 * "cast via DateTime, but leave `null` alone" - exactly what a `?T` typed
 * Command property needs when the SPA legitimately sends `{field: null}`.
 *
 * Wraps the **whole** downstream chain when used with pipeline syntax:
 *   `?int|bool`  -> null -> null
 *                  "1"  -> Integer("1") -> 1 -> Boolean(1) -> true
 *
 * Distinct from {@see Nullable} (the `'nullable'` directive),
 * which **coerces** empty-ish values (`""`, `"null"`) to null. This one
 * never transforms anything - it only short-circuits on already-null
 * inputs.
 */
final class NullSafe implements CasterInterface
{
    private(set) string $name;

    public function __construct(
        private readonly CasterInterface $inner,
    ) {
        $this->name = '?' . $inner->name;
    }

    public function cast(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return $this->inner->cast($value);
    }
}

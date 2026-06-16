<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Provides a fallback value for null or empty strings.
 *
 * Usage: "default:fallback" -> null -> "fallback", "" -> "fallback", "value" -> "value"
 */
final class DefaultValue implements CasterInterface
{
    private(set) string $name = 'default';

    public function __construct(
        private readonly string $default,
    ) {}

    public function cast(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return $this->default;
        }

        return $value;
    }
}

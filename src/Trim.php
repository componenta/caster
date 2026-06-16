<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Trims whitespace from strings.
 *
 * """  hello  " -> "hello"
 */
final class Trim implements CasterInterface
{
    private(set) string $name = 'trim';

    public function __construct(
        private readonly string $characters = " \t\n\r\0\x0B",
    ) {}

    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        return trim($value, $this->characters);
    }
}

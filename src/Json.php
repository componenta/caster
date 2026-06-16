<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Decodes a JSON string into an array or scalar.
 *
 * ""'{"a":1}' -> ['a' => 1], '[1,2]' -> [1,2]
 */
final class Json implements CasterInterface
{
    private(set) string $name = 'json';

    public function cast(mixed $value): mixed
    {
        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new CasterException($value, $this->name);
        }

        return $decoded;
    }
}

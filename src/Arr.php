<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Casts values to array.
 *
 * Supported input types:
 * - array: returned as-is
 * - string: JSON-decoded
 * - object: cast via (array) / get_object_vars
 */
final class Arr implements CasterInterface
{
    private(set) string $name = 'array';

    public function cast(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new CasterException($value, $this->name);
            }

            return (array) $decoded;
        }

        if (is_object($value)) {
            return get_object_vars($value);
        }

        throw new CasterException($value, $this->name);
    }
}

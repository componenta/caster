<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Splits a comma-separated string into an array of integers.
 *
 * Supported input types:
 * - array: each element cast to int
 * - string: split by separator, each element cast to int
 *
 * "1,2,3" -> [1, 2, 3]
 */
final class CsvInt implements CasterInterface
{
    private(set) string $name = 'csv_int';

    public function __construct(
        private readonly string $separator = ',',
    ) {}

    public function cast(mixed $value): array
    {
        if (is_array($value)) {
            return array_map('intval', $value);
        }

        if (is_string($value)) {
            if ($value === '') {
                return [];
            }

            return array_map('intval', explode($this->separator, $value));
        }

        throw new CasterException($value, $this->name);
    }
}

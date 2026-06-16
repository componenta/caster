<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Splits a comma-separated string into an array of trimmed strings.
 *
 * Supported input types:
 * - array: returned as-is
 * - string: split by separator
 *
 * "published,draft" -> ['published', 'draft']
 */
final class Csv implements CasterInterface
{
    private(set) string $name = 'csv';

    public function __construct(
        private readonly string $separator = ',',
    ) {}

    public function cast(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            if ($value === '') {
                return [];
            }

            return array_map('trim', explode($this->separator, $value));
        }

        throw new CasterException($value, $this->name);
    }
}

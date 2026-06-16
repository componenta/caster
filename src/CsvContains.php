<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Checks whether a CSV string (or array) contains a specific value.
 *
 *   "sessions,posts" + needle "sessions" -> true
 *   "posts"          + needle "sessions" -> false
 *   ""               -> false (empty container can't contain anything)
 *
 * Strict on type: non-string / non-array input throws. Use `?csv_contains:...`
 * if the source is nullable.
 */
final class CsvContains implements CasterInterface
{
    private(set) string $name = 'csv_contains';

    public function __construct(
        private readonly string $needle,
        private readonly string $separator = ',',
    ) {}

    public function cast(mixed $value): bool
    {
        if (is_array($value)) {
            return in_array($this->needle, $value, true);
        }

        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        // Empty container - semantically "no match" rather than an error;
        // common UX shape (no checkboxes ticked -> submit empty string).
        if ($value === '') {
            return false;
        }

        $items = array_map('trim', explode($this->separator, $value));

        return in_array($this->needle, $items, true);
    }
}

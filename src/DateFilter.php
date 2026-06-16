<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Parses a smart date filter string into a structured array.
 *
 * Supported formats:
 * - "from:26.06.2026"           -> ['from' => '2026-06-26']
 * - "to:26.06.2026"             -> ['to' => '2026-06-26']
 * - "26.06.2026:30.06.2026"     -> ['from' => '2026-06-26', 'to' => '2026-06-30']
 * - "26.06.2026,27.06.2026"     -> ['dates' => ['2026-06-26', '2026-06-27']]
 * - "26.06.2026"                -> ['dates' => ['2026-06-26']]
 *
 * @return array{from?: string, to?: string, dates?: list<string>}|null
 */
final class DateFilter implements CasterInterface
{
    private(set) string $name = 'date_filter';

    public function __construct(
        private readonly string $format = 'd.m.Y',
    ) {}

    public function cast(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value) || $value === '') {
            throw new CasterException($value, $this->name);
        }

        // from:dd.mm.yyyy
        if (str_starts_with($value, 'from:')) {
            $date = $this->parseDate(substr($value, 5));
            return $date !== null ? ['from' => $date] : [];
        }

        // to:dd.mm.yyyy
        if (str_starts_with($value, 'to:')) {
            $date = $this->parseDate(substr($value, 3));
            return $date !== null ? ['to' => $date] : [];
        }

        // dd.mm.yyyy:dd.mm.yyyy (range)
        if (str_contains($value, ':')) {
            [$fromRaw, $toRaw] = explode(':', $value, 2);
            $result = [];

            if ($fromRaw !== '') {
                $from = $this->parseDate($fromRaw);
                if ($from !== null) {
                    $result['from'] = $from;
                }
            }

            if ($toRaw !== '') {
                $to = $this->parseDate($toRaw);
                if ($to !== null) {
                    $result['to'] = $to;
                }
            }

            return $result;
        }

        // dd.mm.yyyy,dd.mm.yyyy (specific dates)
        $parts = array_map('trim', explode(',', $value));
        $dates = [];

        foreach ($parts as $part) {
            $date = $this->parseDate($part);
            if ($date !== null) {
                $dates[] = $date;
            }
        }

        return $dates !== [] ? ['dates' => $dates] : [];
    }

    private function parseDate(string $value): ?string
    {
        $date = \DateTimeImmutable::createFromFormat($this->format, trim($value));

        return $date !== false ? $date->format('Y-m-d') : null;
    }
}

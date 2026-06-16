<?php

declare(strict_types=1);

namespace Componenta\Caster;

use Componenta\Clock\DateTimeFactory;
use Componenta\Clock\DateTimeFactoryInterface;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * Casts a datetime-shaped value to its RFC 3339 string in UTC.
 *
 * Inverse of {@see DateTime}: input pipeline maps any wire shape (raw
 * string, timestamp, framework `DateTimeInterface`) to a normalized
 * `DateTimeImmutable` via the shared {@see DateTimeFactoryInterface},
 * then renders the canonical `c`-format with the timezone forced to UTC.
 *
 * Used at the OUTPUT boundary (Fetcher -> JSON) - the inverse role of
 * `'datetime'`, which casts inbound HTTP strings into the typed
 * `DateTimeImmutable` properties on Commands. Both share the same factory
 * so test clocks / TZ overrides stay in one place.
 *
 * **Null is intentionally NOT short-circuited** here - null-safety is the
 * caller's responsibility via the `?` prefix in cast directives
 * (`?rfc3339`). This keeps the caster dumb-and-strict, makes nullability
 * a one-place concern, and preserves the invariant that any registered
 * caster always runs when invoked.
 */
final class RFC3339 implements CasterInterface
{
    private static ?DateTimeZone $utc = null;

    public string $name { get => 'rfc3339'; }

    public function __construct(
        private readonly DateTimeFactoryInterface $factory = new DateTimeFactory('UTC'),
    ) {}

    public function cast(mixed $value): string
    {
        $dateTime = match (true) {
            $value instanceof DateTimeImmutable => $value,
            $value instanceof DateTimeInterface => $this->factory->fromInterface($value),
            is_int($value)                      => $this->factory->fromTimestamp($value),
            is_string($value)                   => $this->parseString($value),
            default => throw new CasterException($value, $this->name),
        };

        return $dateTime->setTimezone(self::$utc ??= new DateTimeZone('UTC'))->format('c');
    }

    private function parseString(string $value): DateTimeImmutable
    {
        // Numeric strings - Unix timestamps; everything else goes through
        // the factory's auto-detecting parser (ISO 8601, RFC 2822, etc.).
        if (is_numeric($value)) {
            return $this->factory->fromTimestamp((int) $value);
        }

        try {
            return $this->factory->parse($value);
        } catch (\Throwable $e) {
            throw new CasterException($value, $this->name, previous: $e);
        }
    }
}

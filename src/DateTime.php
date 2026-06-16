<?php

declare(strict_types=1);

namespace Componenta\Caster;

use DateTimeImmutable;
use DateTimeInterface;
use Componenta\Clock\DateTimeFactory;
use Componenta\Clock\DateTimeFactoryInterface;

/**
 * Casts various datetime representations to DateTimeImmutable.
 *
 * Supported input types:
 * - DateTimeInterface: Converted to DateTimeImmutable in factory's timezone
 * - int: Treated as Unix timestamp
 * - string: Parsed using automatic format detection
 */
final class DateTime implements CasterInterface
{
    private(set) string $name = 'datetime';

    public function __construct(
        private readonly DateTimeFactoryInterface $factory = new DateTimeFactory('UTC'),
    ) {
    }

    public function cast(mixed $value): DateTimeImmutable
    {
        return match (true) {
            $value instanceof DateTimeInterface => $this->factory->fromInterface($value),
            is_int($value) => $this->factory->fromTimestamp($value),
            is_string($value) => $this->castString($value),
            default => throw new CasterException($value, $this->name),
        };
    }

    /**
     * @throws CasterException
     */
    private function castString(string $value): DateTimeImmutable
    {
        try {
            return $this->factory->parse($value);
        } catch (\Throwable $e) {
            throw new CasterException(
                $value,
                $this->name,
                previous: $e,
            );
        }
    }
}
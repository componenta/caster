<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Casts a value to a backed enum case.
 *
 * Usage: "enum:App\Status" -> Status::from($value)
 *
 * "published" -> Status::Published
 */
final class EnumValue implements CasterInterface
{
    private(set) string $name = 'enum';

    /**
     * @param class-string<\BackedEnum> $enumClass
     */
    public function __construct(
        private readonly string $enumClass,
    ) {}

    public function cast(mixed $value): \BackedEnum
    {
        if ($value instanceof $this->enumClass) {
            return $value;
        }

        if (!is_int($value) && !is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        return $this->enumClass::from($value);
    }
}

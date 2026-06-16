<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Applies a caster to each element of an array.
 *
 * Usage: "map:int" -> ['1','2'] -> [1,2]
 */
final class Map implements CasterInterface
{
    private(set) string $name = 'map';

    public function __construct(
        private readonly CasterInterface $itemCaster,
    ) {}

    public function cast(mixed $value): array
    {
        if (!is_array($value)) {
            throw new CasterException($value, $this->name);
        }

        return array_map($this->itemCaster->cast(...), $value);
    }
}

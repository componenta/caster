<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Removes duplicate values from an array.
 *
 * ""[1,2,2,3] -> [1,2,3]
 */
final class Unique implements CasterInterface
{
    private(set) string $name = 'unique';

    public function cast(mixed $value): array
    {
        if (!is_array($value)) {
            throw new CasterException($value, $this->name);
        }

        return array_values(array_unique($value));
    }
}

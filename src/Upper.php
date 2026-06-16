<?php

declare(strict_types=1);

namespace Componenta\Caster;

final class Upper implements CasterInterface
{
    private(set) string $name = 'upper';

    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        return mb_strtoupper($value);
    }
}

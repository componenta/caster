<?php

declare(strict_types=1);

namespace Componenta\Caster;

final class Str implements CasterInterface
{
    private(set) string $name = 'string';

    public function cast(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        throw new CasterException($value, $this->name);
    }
}

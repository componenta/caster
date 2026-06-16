<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Decodes a base64-encoded string. Strict - non-string input rejected;
 * use `?base64` if the source is nullable.
 */
final class Base64 implements CasterInterface
{
    private(set) string $name = 'base64';

    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        $decoded = base64_decode($value, true);

        if ($decoded === false) {
            throw new CasterException($value, $this->name);
        }

        return $decoded;
    }
}

<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Strips HTML/PHP tags from a string.
 *
 * """<b>hello</b>" -> "hello"
 */
final class StripTags implements CasterInterface
{
    private(set) string $name = 'strip_tags';

    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        return strip_tags($value);
    }
}

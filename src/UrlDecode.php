<?php

declare(strict_types=1);

namespace Componenta\Caster;


final class UrlDecode implements CasterInterface
{
    private(set) string $name = 'url_decode';

    public function cast(mixed $value): string
    {

        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        return urldecode($value);
    }
}

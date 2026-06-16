<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Filters an array to only include whitelisted keys.
 *
 * Usage: "keys:name,email" -> ['name' => 'a', 'age' => 5] -> ['name' => 'a']
 */
final class Keys implements CasterInterface
{
    private(set) string $name = 'keys';

    /** @param list<string> $allowedKeys */
    public function __construct(
        private readonly array $allowedKeys,
    ) {}

    public function cast(mixed $value): array
    {
        if (!is_array($value)) {
            throw new CasterException($value, $this->name);
        }

        return array_intersect_key($value, array_flip($this->allowedKeys));
    }
}

<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Chains multiple casters sequentially.
 *
 * Each caster receives the output of the previous one:
 * "int|bool" on "1" -> Integer("1") -> 1 -> Boolean(1) -> true
 */
final class Pipeline implements CasterInterface
{
    private(set) string $name;

    /** @param CasterInterface[] $casters */
    public function __construct(
        private readonly array $casters,
    ) {
        $this->name = implode('|', array_map(
            static fn(CasterInterface $c) => $c->name,
            $casters,
        ));
    }

    public function cast(mixed $value): mixed
    {
        foreach ($this->casters as $caster) {
            $value = $caster->cast($value);
        }

        return $value;
    }
}

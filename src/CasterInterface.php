<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Represents a generic type caster.
 *
 * Implementations of this interface are responsible for transforming
 * a given value into a specific type or format.
 */
interface CasterInterface
{
    /**
     * The caster name used for identification and registry lookup.
     */
    public string $name { get; }

    /**
     * Casts the provided value to a specific type.
     *
     * Implementations should define how the casting is performed and ensure
     * that the returned value conforms to the intended type or format.
     *
     * @param mixed $value The value to be cast.
     * @return mixed The transformed value after casting.
     *
     * @throws CasterExceptionInterface If the provided value cannot be cast properly.
     */
    public function cast(mixed $value): mixed;
}

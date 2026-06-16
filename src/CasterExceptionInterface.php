<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Exception thrown when a value cannot be cast properly.
 */
interface CasterExceptionInterface extends \Throwable
{
    /**
     * The value that failed to cast.
     */
    public mixed $value { get; }

    /**
     * The caster name that failed.
     */
    public string $casterName { get; }
}

<?php

declare(strict_types=1);

namespace Componenta\Caster;

use OutOfBoundsException;

/**
 * Exception thrown when a requested caster is not found in the provider.
 */
class CasterNotFoundException extends OutOfBoundsException
{
    /**
     * @param string $name The caster name that was not found.
     */
    public function __construct(
        public readonly string $name,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            $message ?: sprintf('Caster "%s" not found', $name),
            $code,
            $previous,
        );
    }

    /**
     * Creates exception for missing caster.
     */
    public static function forName(string $name): self
    {
        return new self($name);
    }

    /**
     * Creates exception for missing pipe segment.
     */
    public static function forPipeSegment(string $segment, string $fullPipe): self
    {
        return new self(
            $segment,
            sprintf('Caster "%s" not found in pipe "%s"', $segment, $fullPipe),
        );
    }
}

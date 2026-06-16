<?php

declare(strict_types=1);

namespace Componenta\Caster;

use Exception;

/**
 * Exception thrown when a value cannot be cast to the expected type.
 */
final class CasterException extends Exception implements CasterExceptionInterface
{
    public mixed $value {
        get => $this->value;
    }

    public string $casterName {
        get => $this->casterName;
    }

    public function __construct(
        mixed $value,
        string $casterName,
        ?string $message = null,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        $this->value = $value;
        $this->casterName = $casterName;

        parent::__construct(
            $message ?? sprintf(
            'Cannot cast value of type "%s" using caster "%s".',
            get_debug_type($value),
            $casterName,
        ),
            $code,
            $previous,
        );
    }
}
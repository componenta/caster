<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Hashes a string value using the specified algorithm.
 *
 * Usage: "hash:md5", "hash:sha256", or shorthand "md5", "sha256"
 *
 * """hello" -> "5d41402abc4b2a76b9719d911017c592" (md5)
 */
final class Hash implements CasterInterface
{
    private(set) string $name = 'hash';

    public function __construct(
        public readonly string $algo = 'md5',
    ) {
        if (!in_array($algo, hash_algos(), true)) {
            throw new \InvalidArgumentException(sprintf('Unknown hash algorithm: "%s"', $algo));
        }
    }

    public function cast(mixed $value): string
    {
        if (!is_string($value)) {
            throw new CasterException($value, $this->name);
        }

        return hash($this->algo, $value);
    }
}

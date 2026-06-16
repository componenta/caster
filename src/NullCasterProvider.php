<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * No-op caster provider that always returns null.
 *
 * Used as a safe default when no real provider is configured.
 */
final class NullCasterProvider implements CasterProviderInterface
{
    public function provide(string $name): ?CasterInterface
    {
        return null;
    }
}

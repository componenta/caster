<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Registry for type caster instances.
 *
 * Enables dynamic retrieval of casters by name, allowing for a flexible
 * type conversion system. Acts as a central repository for all available
 * casters in an application.
 *
 * Supports composite names using pipe syntax (e.g., "base64|json")
 * for sequential transformations.
 */
interface CasterProviderInterface
{
    /**
     * Provides a caster instance based on its name.
     *
     * Retrieves the caster registered under the specified name. If a composite
     * name is provided (using pipe syntax, e.g., "string|json"), implementations
     * should handle the creation of appropriate composite casters.
     *
     * @param string $name The name of the caster to retrieve.
     * @return CasterInterface|null The caster instance if found; otherwise, null.
     */
    public function provide(string $name): ?CasterInterface;
}

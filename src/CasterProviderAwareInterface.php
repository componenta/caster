<?php

declare(strict_types=1);

namespace Componenta\Caster;

interface CasterProviderAwareInterface
{
    public CasterProviderInterface $provider {
        set;
    }
}

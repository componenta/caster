<?php

declare(strict_types=1);

namespace Componenta\Caster;

class ConfigProvider extends \Componenta\Config\ConfigProvider
{
    protected function getFactories(): array
    {
        return [CasterProviderInterface::class => static fn(): CasterProviderInterface => new CasterProvider()];
    }
}

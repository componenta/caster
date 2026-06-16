<?php

declare(strict_types=1);

namespace Componenta\Caster\Tests;

use Componenta\Caster\CasterException;
use Componenta\Caster\CasterInterface;
use Componenta\Caster\CasterProvider;
use Componenta\Caster\CasterProviderInterface;
use Componenta\Caster\ConfigProvider;
use Componenta\Config\ConfigKey;
use PHPUnit\Framework\TestCase;

final class CasterProviderTest extends TestCase
{
    public function testProvidesDefaultCasterByName(): void
    {
        $provider = new CasterProvider();
        $caster = $provider->provide('int');

        self::assertInstanceOf(CasterInterface::class, $caster);
        self::assertSame('int', $caster->name);
        self::assertSame(42, $caster->cast('42'));
    }

    public function testReturnsNullForUnknownCaster(): void
    {
        self::assertNull((new CasterProvider())->provide('missing'));
    }

    public function testSupportsParameterizedCaster(): void
    {
        $caster = (new CasterProvider())->provide('clamp:10,20');

        self::assertInstanceOf(CasterInterface::class, $caster);
        self::assertSame(10.0, $caster->cast(5));
        self::assertSame(15.0, $caster->cast(15));
        self::assertSame(20.0, $caster->cast(25));
    }

    public function testSupportsPipelines(): void
    {
        $caster = (new CasterProvider())->provide('trim|lower');

        self::assertInstanceOf(CasterInterface::class, $caster);
        self::assertSame('hello', $caster->cast('  HELLO  '));
    }

    public function testNullSafePipelineLeavesNullUntouched(): void
    {
        $caster = (new CasterProvider())->provide('?int|bool');

        self::assertInstanceOf(CasterInterface::class, $caster);
        self::assertNull($caster->cast(null));
        self::assertTrue($caster->cast('1'));
    }

    public function testInvalidCastThrowsTypedException(): void
    {
        $caster = (new CasterProvider())->provide('int');

        $this->expectException(CasterException::class);

        $caster?->cast([]);
    }

    public function testConfigProviderRegistersCasterProviderContract(): void
    {
        $config = (new ConfigProvider())();
        $factory = $config[ConfigKey::DEPENDENCIES][ConfigKey::FACTORIES][CasterProviderInterface::class];

        self::assertInstanceOf(CasterProviderInterface::class, $factory());
    }
}

# Componenta Caster

Named value casters and casting pipelines.

## Installation

```bash
composer require componenta/caster
```

The package declares `Componenta\Caster\ConfigProvider` in `extra.componenta.config-providers`.
When `componenta/composer-plugin` is installed, the provider is added to the generated provider list automatically.

## Requirements

- PHP 8.4+

## Related Packages

| Package | Why it matters here |
|---|---|
| `componenta/di` | Uses casters in `#[Cast]`, `#[QueryParam(cast: ...)]`, and similar attributes. |
| `componenta/validation` | Can validate DTOs after values are normalized by casters. |
| `componenta/config` | Registers `CasterProviderInterface` through `ConfigProvider`. |

## What It Provides

- `CasterInterface`: public contract for named value casters.
- `CasterProviderInterface`: registry contract for resolving casters by name.
- `CasterProvider`: default registry with scalar, string, array, date, enum, hash, and pipeline casters.
- Typed exceptions when a value cannot be cast.

## Basic Usage

```php
use Componenta\Caster\CasterProvider;

$provider = new CasterProvider();

$int = $provider->provide('int');
$int->cast('42'); // 42
```

Unknown casters return `null`:

```php
$provider->provide('missing'); // null
```

## Parameterized Casters

```php
$provider->provide('clamp:10,20')->cast(25); // 20.0
$provider->provide('csv_int')->cast('1,2,3'); // [1, 2, 3]
```

## Pipelines

Use `|` to chain casters:

```php
$provider->provide('trim|lower')->cast('  HELLO  '); // hello
```

Use `?` to pass `null` through the whole downstream chain:

```php
$provider->provide('?int|bool')->cast(null); // null
$provider->provide('?int|bool')->cast('1');  // true
```

## DI Registration

`ConfigProvider` registers `CasterProviderInterface` as a factory returning `CasterProvider`.

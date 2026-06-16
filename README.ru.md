# Componenta Caster

Именованные преобразователи значений. Пакет берёт строковое имя вроде `int`, `trim|lower` или `clamp:10,20` и возвращает объект, который приводит значение к нужной форме.

## Установка

```bash
composer require componenta/caster
```

Пакет объявляет `Componenta\Caster\ConfigProvider` в `extra.componenta.config-providers`.
Если установлен `componenta/composer-plugin`, провайдер автоматически добавляется в сгенерированный список провайдеров.

## Требования

- PHP 8.4+

## Связанные пакеты

| Пакет | Зачем нужен здесь |
|---|---|
| `componenta/di` | Использует кастеры в атрибутах `#[Cast]`, `#[QueryParam(cast: ...)]` и похожих местах. |
| `componenta/validation` | Может использовать кастеры перед валидацией DTO, если это настроено на уровне приложения. |
| `componenta/config` | Регистрирует `CasterProviderInterface` через `ConfigProvider`. |

## Что предоставляет пакет

- `CasterInterface`: интерфейс именованного преобразователя.
- `CasterProviderInterface`: интерфейс поиска преобразователя по имени.
- `CasterProvider`: реализация по умолчанию со scalar, string, array, date, enum, hash и pipeline преобразователями.
- Типизированные исключения, когда значение нельзя привести.

## Базовое использование

```php
use Componenta\Caster\CasterProvider;

$provider = new CasterProvider();

$int = $provider->provide('int');
$int->cast('42'); // 42
```

Неизвестный caster возвращает `null`:

```php
$provider->provide('missing'); // null
```

## Преобразователи с параметрами

```php
$provider->provide('clamp:10,20')->cast(25); // 20.0
$provider->provide('csv_int')->cast('1,2,3'); // [1, 2, 3]
```

## Цепочки

Используйте `|`, чтобы связать несколько преобразователей:

```php
$provider->provide('trim|lower')->cast('  HELLO  '); // hello
```

Используйте `?`, чтобы пропустить `null` через всю цепочку:

```php
$provider->provide('?int|bool')->cast(null); // null
$provider->provide('?int|bool')->cast('1');  // true
```

## DI-регистрация

`ConfigProvider` регистрирует `CasterProviderInterface` через фабрику, возвращающую `CasterProvider`.

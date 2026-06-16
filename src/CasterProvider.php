<?php

declare(strict_types=1);

namespace Componenta\Caster;

/**
 * Registry for type casters.
 *
 * Supports:
 * - Simple casters: `int`, `bool`, `csv`
 * - Parameterized casters: `csv_contains:sessions` -> factory receives `['sessions']`
 * - Pipeline syntax: `int|bool` -> chains casters sequentially (`'1' -> 1 -> true`)
 * - Custom factories: `register('my_caster', fn(array $p) => new MyCaster($p[0]))`
 */
class CasterProvider implements CasterProviderInterface
{
    /** @var array<string, callable(array): CasterInterface> */
    private array $factories = [];

    /** @var array<string, CasterInterface> */
    private array $cache = [];

    public function __construct()
    {
        $this->registerDefaults();
    }

    /**
     * Registers a caster factory.
     *
     * @param string $name Caster name.
     * @param callable(array): CasterInterface $factory Receives parsed parameters array.
     */
    public function register(string $name, callable $factory): self
    {
        $this->factories[$name] = $factory;

        return $this;
    }

    public function provide(string $name): ?CasterInterface
    {
        // Null-safe wrapper: "?datetime" means "leave null alone, otherwise
        // run the rest of the chain". The `?` always binds to the WHOLE
        // tail (including `|`-pipelines) - wrapping the chain rather than
        // a single segment is what makes `?int|bool` short-circuit on null
        // before the int caster sees it.
        if ($name !== '' && $name[0] === '?') {
            $inner = $this->provide(substr($name, 1));

            return $inner !== null ? new NullSafe($inner) : null;
        }

        // Pipeline: "int|bool"
        if (str_contains($name, '|')) {
            return $this->createPipeline($name);
        }

        return $this->createSingle($name);
    }

    private function createSingle(string $definition): ?CasterInterface
    {
        if (isset($this->cache[$definition])) {
            return $this->cache[$definition];
        }

        if (str_contains($definition, ':')) {
            $colonPos = strpos($definition, ':');
            $name = substr($definition, 0, $colonPos);
            $params = array_map('trim', explode(',', substr($definition, $colonPos + 1)));

            $factory = $this->factories[$name] ?? null;

            return $factory !== null ? $factory($params) : null;
        }

        $factory = $this->factories[$definition] ?? null;

        if ($factory === null) {
            // Fallback: try as hash algorithm name (md5, sha256, etc.)
            if (in_array($definition, hash_algos(), true)) {
                $caster = new Hash($definition);
                $this->cache[$definition] = $caster;

                return $caster;
            }

            return null;
        }

        $caster = $factory([]);
        $this->cache[$definition] = $caster;

        return $caster;
    }

    private function createPipeline(string $definition): ?CasterInterface
    {
        $casters = [];

        foreach (explode('|', $definition) as $segment) {
            $caster = $this->createSingle(trim($segment));

            if ($caster === null) {
                return null;
            }

            $casters[] = $caster;
        }

        return new Pipeline($casters);
    }

    private function registerDefaults(): void
    {
        // Scalars
        $this->register('int', static fn() => new Integer);
        $this->register('bool', static fn() => new Boolean);
        $this->register('float', static fn() => new Decimal);
        $this->register('string', static fn() => new Str);
        $this->register('array', static fn() => new Arr);
        $this->register('nullable', static fn() => new Nullable);

        // Strings
        $this->register('trim', static fn() => new Trim);
        $this->register('lower', static fn() => new Lower);
        $this->register('upper', static fn() => new Upper);
        $this->register('strip_tags', static fn() => new StripTags);
        $this->register('base64', static fn() => new Base64);
        $this->register('url_decode', static fn() => new UrlDecode);
        $this->register('json', static fn() => new Json);

        // Numeric
        $this->register('abs', static fn() => new Abs);
        $this->register('round', static fn(array $p) => new Round((int) ($p[0] ?? 0)));
        $this->register('clamp', static fn(array $p) => new Clamp((float) ($p[0] ?? 0), (float) ($p[1] ?? PHP_FLOAT_MAX)));

        // Arrays
        $this->register('csv', static fn() => new Csv);
        $this->register('csv_int', static fn() => new CsvInt);
        $this->register('csv_contains', static fn(array $p) => new CsvContains($p[0] ?? ''));
        $this->register('explode', static fn(array $p) => new Explode($p[0] ?? ','));
        $this->register('implode', static fn(array $p) => new Implode($p[0] ?? ','));
        $this->register('map', fn(array $p) => new Map($this->provide($p[0] ?? 'string') ?? new Str));
        $this->register('unique', static fn() => new Unique);
        $this->register('keys', static fn(array $p) => new Keys($p));

        // Default/fallback
        $this->register('default', static fn(array $p) => new DefaultValue($p[0] ?? ''));

        // Dates
        $this->register('date_filter', static fn() => new DateFilter);
        $this->register('datetime', static fn() => new DateTime);
        $this->register('rfc3339', static fn() => new RFC3339);

        // Enum
        $this->register('enum', static fn(array $p) => new EnumValue($p[0] ?? ''));

        // Hash - "hash:md5", "hash:sha256"; shorthand "md5", "sha256" resolved via fallback
        $this->register('hash', static fn(array $p) => new Hash($p[0] ?? 'md5'));
    }
}

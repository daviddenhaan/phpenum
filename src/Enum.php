<?php

namespace Dulkoss\Phpenum;

use LogicException;

abstract class Enum
{
    protected const variants = [];

    protected string $variant;

    protected mixed $value;

    protected function __construct(string $variant, $value)
    {
        $this->variant = $variant;

        [$verified, $expected, $actual] = self::verifyType($variant, $value);

        if (! $verified) {
            throw new LogicException("Enum variant \"{$variant}\" expected inner value to be of type [{$expected}], got [{$actual}]");
        }

        $this->value = $value;
    }

    public function matches(Enum $enum): bool
    {
        if (! $enum instanceof static) {
            return false;
        }

        return $enum->isVariant($this->variant);
    }

    /**
     * @return array|false|static
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (! self::hasVariant($name)) {
            $enum = static::class;

            throw new LogicException("Enum [{$enum}] does not have a variant named [{$name}]");
        }

        $instance = array_shift($arguments);

        if (self::resolveType($instance) === static::class) {
            return self::resolveValue($name, $instance);
        }

        return new static($name, $instance);
    }

    protected function isVariant(string $name): bool
    {
        return $this->variant === $name;
    }

    protected static function hasVariant(string $name): bool
    {
        return isset(static::variants[$name]);
    }

    /**
     * @return array|false
     */
    private static function resolveValue(string $variant, Enum $instance)
    {
        if (! $instance->isVariant($variant)) {
            return false;
        }

        return [$instance->value];
    }

    private static function resolveType($value): string
    {
        $type = gettype($value);

        if ($type === 'object') {
            $type = get_class($value);
        }

        return $type;
    }

    private function verifyType(string $variant, $against): array
    {
        $expected = static::variants[$variant];

        $actual = self::resolveType($against);

        if (interface_exists($expected)) {
            return [$against instanceof $expected, $expected, $actual];
        }

        if ($expected === 'mixed') {
            return [true, $expected, $actual];
        }

        return [$actual === $expected, $expected, $actual];
    }
}
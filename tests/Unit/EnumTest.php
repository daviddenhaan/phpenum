<?php

use Dulkoss\Phpenum\Enum;

test('enum can have an inner value', function () {
    $result = Result::Ok('hello');

    expect($result)
        ->toBeInstanceOf(Result::class);
});

test('inner value can be unwrapped', function () {
    $result = Result::Ok('hello');

    [$value] = Result::Ok($result);

    expect($value)
        ->toBe('hello');
});

test('inner value can be conditionally unwrapped and used', function () {
    $result = Result::Ok('hello');

    if (! [$value] = Result::Ok($result)) {
        $this->fail('failed to unwrap value');
    }

    expect($value)
        ->toBe('hello');
});

test('enum variants can be compared', function () {
    $n1 = Option::None();
    $n2 = Option::None();

    expect($n1->matches($n2))
        ->toBeTrue();
});

test('enum variants with inner values can be compared', function () {
    $o1 = Option::Some('Hello');

    $o2 = Option::Some('Bye');

    expect($o1->matches($o2))
        ->toBeTrue();
});

test('different variants of the same enum do not match', function () {
    $some = Option::Some('something');
    $none = Option::None();

    expect($some->matches($none))
        ->toBeFalse();
});

test('empty enum variant has null as inner value', function () {
    $none = Option::None();

    [$value] = Option::None($none);

    expect($value)
        ->toBeNull();
});

/**
 * @method static Result Ok(mixed|Result $value)
 * @method static Result Err(\Throwable|Result $value)
 */
class Result extends Enum
{
    protected const variants = [
        'Ok' => 'mixed',
        'Err' => \Throwable::class,
    ];
}

/**
 * @template T
 *
 * @method static array<mixed>|Option Some(mixed|Option $value)
 * @method static array<null>|Option None(?Option $unwrap = null)
 */
class Option extends Enum
{
    protected const variants = [
        'Some' => 'mixed',
        'None' => 'NULL',
    ];
}

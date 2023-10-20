<?php

use Dulkoss\Phpenum\Enum;

require __DIR__ . '/../vendor/autoload.php';

/**
 * @method static array<mixed>|Result Ok(mixed|Result $value)
 * @method static array<Error>|Result Err(Error|Result $value)
 */
class Result extends Enum
{
    protected const variants = [
        'Ok' => 'mixed',
        'Err' => Error::class,
    ];

    public function unwrap(): mixed
    {
        if ([$value] = static::Ok($this)) {
            return $value;
        }

        [$error] = static::Err($this);

        throw $error;
    }
}

$result = Result::Ok('hello!');

Result::Err(new class extends Error {
    // this could be a result too!
});

if ([$e] = Result::Err($result)) {
    echo 'Oopsie' . PHP_EOL;
} else {
    echo $result->unwrap() . PHP_EOL;
}


<?php

use Dulkoss\Phpenum\Enum;

require __DIR__ . '/../vendor/autoload.php';

/**
 * @method static array<mixed>|Option Some(mixed|Option $value)
 * @method static array<null>|Option None()
 */
class Option extends Enum
{
    protected const variants = [
        'Some' => 'mixed',
        'None' => 'NULL',
    ];
}

$option = Option::Some('hello!');

[$value] = Option::Some($option);

echo $value . PHP_EOL;
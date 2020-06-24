<?php

use \Faker\Generator;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Mechawrench\Laracoins\Models\Coin::class, function (Generator $faker) {
    return [
        'user_id' => $faker->numberBetween(0, 2000000),
        'quantity' => $faker->numberBetween(0, 2000),
    ];
});

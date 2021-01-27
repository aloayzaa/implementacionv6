<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;


$factory->define(App\Plan::class, function (Faker $faker) {
    return [
        'name' => 'Demo',
        'price' => '0.00',
        'months' => 1,
    ];
});



<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'state'=> 0,
        'phone'=> '565656',
        'email'=> $faker->unique()->safeEmail,
        'verified'=> $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'verification_token'=> $verificado == User::USUARIO_VERIFICADO ? null : User::generaVerificationToken(),
        'password'=> bcrypt('123'),
    ];
});

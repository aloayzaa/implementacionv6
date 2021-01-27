<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Subscription;
use Carbon\Carbon;
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

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'company_id'=> 1,
        'plan_id'=> 1,
        'started_at'=> Carbon::now()->format('Y-m-d'),
        'finished_at'=> Carbon::now()->addMonths(1)->format('Y-m-d'),
        'renewal'=> Subscription::RENUEVA_SUSCRIPCION,
    ];
});



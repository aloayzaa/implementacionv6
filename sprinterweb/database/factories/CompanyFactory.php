<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;


$factory->define(App\Company::class, function (Faker $faker) {
    $users = User::all();
    $user = $users->random();
    return [
        'name' => 'Anikama',
        'ruc'=> '20109072177',
        'contact'=> $user->name,
        'direction'=> 'algun sitio',
        'ubigeo_code'=> '150122',
        'active'=> '1',
        'user_id' => $user->id,
    ];
});

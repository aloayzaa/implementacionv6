<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->create(
            [
                'name' => 'Ramon',
                'state'=> 0,
                'phone'=> '565656',
                'verified'=> User::USUARIO_VERIFICADO,
                'verification_token'=> null,
                'email'=> 'dany@anikamagroup.com',
                'password'=> bcrypt('123'),
            ]
        );
    }
}

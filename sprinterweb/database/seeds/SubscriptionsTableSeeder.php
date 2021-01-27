<?php

use App\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Subscription::class, 1)->create(
            ['name' => 'Suscripcion 1']
        );
    }
}

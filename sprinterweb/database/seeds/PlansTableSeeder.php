<?php

use App\Plan;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Plan::class, 1)->create();
        factory(Plan::class, 1)->create([
            'name' => 'Estandar',
            'price' => '70',
        ]);
        factory(Plan::class, 1)->create([
            'name' => 'Anual',
            'price' => '500',
            'months' => 12,
        ]);
    }
}

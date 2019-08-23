<?php

use Illuminate\Database\Seeder;

class SolicitationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Model\Solicitation::class, 50)->create();
    }
}

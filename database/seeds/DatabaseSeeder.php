<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(VolunteerTableSeeder::class);
        $this->call(InstitutionTableSeeder::class);
        $this->call(WorkTableSeeder::class);
        $this->call(RatingTableSeeder::class);
        $this->call(SolicitationTableSeeder::class);
    }
}

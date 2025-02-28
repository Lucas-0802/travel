<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            TravelOrderStatusSeeder::class,
            TravelTypesSeeder::class,
            CitiesSeeder::class,
        ]);
    }
}

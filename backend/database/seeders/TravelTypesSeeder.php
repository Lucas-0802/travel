<?php

namespace Database\Seeders;

use App\Models\TravelType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TravelTypesSeeder extends Seeder
{
    public function run(): void
    {   
        $types = ['Hotel', 'Plane', 'Bus', 'Car'];

        foreach ($types as $type) {
            TravelType::create(['name' => $type]);
        }
    }
}

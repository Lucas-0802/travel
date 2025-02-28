<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TravelOrderStatus;

class TravelOrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $status_name = ['requested', 'approved', 'canceled', 'requested_cancellation'];

        foreach ($status_name as $status) {
            TravelOrderStatus::create(['name' => $status]);
        }
    }
}

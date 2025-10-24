<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::firstOrCreate([
            'name' => 'Basic'
        ], [
            'description' => 'Default free plan for all users',
            'price' => 0,
            'duration' => 0
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Create Users
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobile' => '9898767611',
            'password' => Hash::make('talha123'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Test2 User',
            'email' => 'test1@example.com',
            'mobile' => '9898767612',
            'password' => Hash::make('talha123'),
            'role' => 'agent'
        ]);

        User::factory()->create([
            'name' => 'Test3 User',
            'email' => 'test2@example.com',
            'mobile' => '9898767613',
            'password' => Hash::make('talha123'),
            'role' => 'supplier'
        ]);

        $this->call([
            PlanSeeder::class,
            SubscriptionSeeder::class
        ]);



    }
}

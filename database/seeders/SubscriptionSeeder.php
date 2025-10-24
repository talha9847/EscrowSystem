<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{

    public function run(): void
    {
        $basicPlan = Plan::where('name', 'Basic')->first();

        if (!$basicPlan) {
            $this->command->warn('⚠️ Basic Plan not found. Run PlanSeeder first.');
            return;
        }

        $users = User::all();

        foreach ($users as $user) {
            Subscription::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'plan_id' => $basicPlan->id,
                ],
                [
                    'start_date' => now(),
                    'end_date' => null,
                    'status' => 'active',
                ]
            );
        }

        $this->command->info("✅ Subscriptions seeded for {$users->count()} users.");
    }
}

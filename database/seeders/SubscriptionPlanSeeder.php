<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'        => 'Free',
                'slug'        => 'free',
                'price'       => 0.00,
                'ebook_limit' => 3,
                'description' => 'Access up to 3 ebooks at a time. Perfect for casual readers.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Basic',
                'slug'        => 'basic',
                'price'       => 99.00,
                'ebook_limit' => 10,
                'description' => 'Access up to 10 ebooks at a time. Great for regular readers.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Premium',
                'slug'        => 'premium',
                'price'       => 199.00,
                'ebook_limit' => -1,
                'description' => 'Unlimited ebook access. Perfect for avid readers.',
                'is_active'   => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}

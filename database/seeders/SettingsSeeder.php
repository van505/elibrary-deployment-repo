<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key'         => 'platform_name',
                'value'       => 'ELibrary',
                'type'        => 'string',
                'description' => 'Platform display name',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'max_access_per_free',
                'value'       => '3',
                'type'        => 'integer',
                'description' => 'Max ebooks free plan can access',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'max_access_per_basic',
                'value'       => '10',
                'type'        => 'integer',
                'description' => 'Max ebooks basic plan can access',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'subscription_basic_price',
                'value'       => '99.00',
                'type'        => 'decimal',
                'description' => 'Monthly price for Basic plan',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'subscription_premium_price',
                'value'       => '199.00',
                'type'        => 'decimal',
                'description' => 'Monthly price for Premium plan',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}

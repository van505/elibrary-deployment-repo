<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key'         => 'fine_rate_per_day',
                'value'       => '5.00',
                'type'        => 'decimal',
                'description' => 'Fine amount per day for overdue books',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'max_borrow_days',
                'value'       => '7',
                'type'        => 'integer',
                'description' => 'Maximum number of days a member can borrow an ebook',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'max_active_borrowings',
                'value'       => '3',
                'type'        => 'integer',
                'description' => 'Maximum number of active borrowings per member',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'max_reservations',
                'value'       => '2',
                'type'        => 'integer',
                'description' => 'Maximum number of active reservations per member',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'membership_duration',
                'value'       => '365',
                'type'        => 'integer',
                'description' => 'Membership validity in days',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}

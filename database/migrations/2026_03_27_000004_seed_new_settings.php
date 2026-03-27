<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'site_name',                   'value' => 'ELibrary',  'type' => 'string',  'description' => 'The name of the site displayed in the header and emails.'],
            ['key' => 'site_logo',                   'value' => '',          'type' => 'file',    'description' => 'Site logo image (stored in storage/settings/).'],
            ['key' => 'max_ebook_access_per_day',    'value' => '10',        'type' => 'integer', 'description' => 'Max number of ebooks a member can access per day.'],
            ['key' => 'allow_registration',          'value' => '1',         'type' => 'boolean', 'description' => 'Allow new members to self-register.'],
            ['key' => 'maintenance_mode',            'value' => '0',         'type' => 'boolean', 'description' => 'Put the site into maintenance mode.'],
            ['key' => 'default_subscription_plan',  'value' => '',           'type' => 'select',  'description' => 'Default subscription plan assigned to new members.'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'site_name', 'site_logo', 'max_ebook_access_per_day',
            'allow_registration', 'maintenance_mode', 'default_subscription_plan',
        ])->delete();
    }
};

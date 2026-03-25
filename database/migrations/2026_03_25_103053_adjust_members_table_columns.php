<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Members table already has user_id (not member_id) and no `name` column.
 * This migration adds the first_name / middle_name / last_name columns.
 * All nullable — member profile is filled separately after Breeze registration.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Add new nullable name columns if they don't exist yet
        Schema::table('members', function (Blueprint $table) {
            if (! Schema::hasColumn('members', 'first_name')) {
                $table->string('first_name')->nullable()->after('member_code');
            }
            if (! Schema::hasColumn('members', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('members', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $cols = ['first_name', 'middle_name', 'last_name'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('members', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

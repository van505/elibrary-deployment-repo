<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Add `level` tinyint to subscription_plans.
 * Inline data seed: Free=0, Basic=1, Premium=2.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('subscription_plans', 'level')) {
                $table->tinyInteger('level')->default(0)->after('is_active');
            }
        });

        // Seed levels for existing plans
        DB::table('subscription_plans')->where('slug', 'free')->update(['level' => 0]);
        DB::table('subscription_plans')->where('slug', 'basic')->update(['level' => 1]);
        DB::table('subscription_plans')->where('slug', 'premium')->update(['level' => 2]);
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_plans', 'level')) {
                $table->dropColumn('level');
            }
        });
    }
};

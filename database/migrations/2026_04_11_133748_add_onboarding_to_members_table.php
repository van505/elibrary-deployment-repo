<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('onboarding_completed')->default(false)->after('avatar');
            $table->tinyInteger('onboarding_step')->default(1)->after('onboarding_completed');
            $table->json('preferred_categories')->nullable()->after('onboarding_step');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['onboarding_completed', 'onboarding_step', 'preferred_categories']);
        });
    }
};

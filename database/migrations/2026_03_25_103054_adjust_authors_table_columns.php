<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Replace the single `name` column in authors with
 * first_name, middle_name, last_name.
 * Data is preserved: existing name is split and copied.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1 — Add new nullable columns
        Schema::table('authors', function (Blueprint $table) {
            if (! Schema::hasColumn('authors', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('authors', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('authors', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }
        });

        // Step 2 — Copy data: split existing `name` into first/last
        if (Schema::hasColumn('authors', 'name')) {
            DB::statement("
                UPDATE authors
                SET
                    first_name = SUBSTRING_INDEX(name, ' ', 1),
                    last_name  = CASE
                        WHEN name LIKE '% %' THEN SUBSTRING_INDEX(name, ' ', -1)
                        ELSE NULL
                    END
                WHERE name IS NOT NULL
            ");

            // Step 3 — Drop the old name column
            Schema::table('authors', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            if (! Schema::hasColumn('authors', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
        });

        $cols = ['first_name', 'middle_name', 'last_name'];
        Schema::table('authors', function (Blueprint $table) use ($cols) {
            foreach ($cols as $col) {
                if (Schema::hasColumn('authors', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

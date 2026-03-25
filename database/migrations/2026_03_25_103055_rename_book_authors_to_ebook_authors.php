<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Rename book_authors → ebook_authors.
 * Note: the create migration already uses `ebook_id` (not `book_id`),
 * so no column rename is needed — only the table rename.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('book_authors') && ! Schema::hasTable('ebook_authors')) {
            Schema::rename('book_authors', 'ebook_authors');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ebook_authors') && ! Schema::hasTable('book_authors')) {
            Schema::rename('ebook_authors', 'book_authors');
        }
    }
};

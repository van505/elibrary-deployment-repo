<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebook_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('ebook_id')->constrained('ebooks')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['member_id', 'ebook_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebook_bookmarks');
    }
};

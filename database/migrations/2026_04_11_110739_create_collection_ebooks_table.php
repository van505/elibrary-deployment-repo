<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->cascadeOnDelete();
            $table->foreignId('ebook_id')->constrained('ebooks')->cascadeOnDelete();
            $table->integer('order_number')->default(0);
            $table->timestamps();
            
            $table->unique(['collection_id', 'ebook_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_ebooks');
    }
};

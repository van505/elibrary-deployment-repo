<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('isbn')->unique()->nullable();
            $table->string('publisher')->nullable();
            $table->integer('publish_year')->nullable();
            $table->string('file_path');
            $table->string('cover_image')->nullable();
            $table->enum('file_type', ['pdf', 'epub', 'mp3'])->default('pdf');
            $table->enum('access_level', ['free', 'basic', 'premium'])->default('free');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};

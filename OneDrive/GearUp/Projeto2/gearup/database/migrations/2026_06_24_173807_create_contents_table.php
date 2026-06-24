<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['course', 'book', 'film']);
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('url')->nullable();
            $table->string('duration')->nullable();
            $table->enum('language', ['pt', 'en', 'es'])->default('pt');
            $table->boolean('is_free')->default(true);
            $table->boolean('has_certificate')->default(false);
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
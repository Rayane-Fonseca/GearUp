<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trilha_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_trilha')->constrained('trilhas')->cascadeOnDelete();
            $table->foreignId('id_curso')->constrained('cursos', 'id_curso')->cascadeOnDelete();
            $table->boolean('obrigatorio')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trilha_curso');
    }
};

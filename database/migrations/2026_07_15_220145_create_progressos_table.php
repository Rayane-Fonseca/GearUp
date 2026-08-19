<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progressos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios', 'id_usuario')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos', 'id_curso')->cascadeOnDelete();
            $table->foreignId('aula_id')->nullable()->constrained('aulas')->nullOnDelete();
            $table->unsignedTinyInteger('porcentagem')->default(0);
            $table->boolean('concluido')->default(false);
            $table->timestamp('concluido_em')->nullable();
            $table->timestamps();

            $table->unique(['usuario_id', 'curso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progressos');
    }
};

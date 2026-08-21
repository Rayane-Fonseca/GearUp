<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarda o progresso individual do aluno em cada aula (tempo assistido do vídeo).
     * A tabela "progressos" continua existindo e passa a representar o progresso
     * consolidado por curso (usuario_id + curso_id), calculado a partir desta tabela.
     */
    public function up(): void
    {
        Schema::create('aula_progressos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios', 'id_usuario')->cascadeOnDelete();
            $table->foreignId('aula_id')->constrained('aulas')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos', 'id_curso')->cascadeOnDelete();
            $table->unsignedInteger('tempo_assistido')->default(0); // em segundos
            $table->unsignedInteger('duracao_total')->default(0); // em segundos
            $table->unsignedTinyInteger('porcentagem')->default(0);
            $table->boolean('concluido')->default(false);
            $table->timestamp('concluido_em')->nullable();
            $table->timestamps();

            $table->unique(['usuario_id', 'aula_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aula_progressos');
    }
};

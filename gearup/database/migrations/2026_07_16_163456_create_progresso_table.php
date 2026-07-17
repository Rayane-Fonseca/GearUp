<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progresso', function (Blueprint $table) {
            $table->id('id_progresso'); // Chave primária da tabela pivô
            
            // Chave estrangeira do usuário (aponta para 'id_usuario' na tabela 'usuario')
            $table->foreignId('id_usuario')
                  ->constrained('usuario', 'id_usuario')
                  ->onDelete('cascade');

            // Chave estrangeira da aula (aponta para 'id_aula' na tabela 'aulas')
            $table->foreignId('id_aula')
                  ->constrained('aulas', 'id_aula')
                  ->onDelete('cascade');

            $table->timestamp('concluido_em')->useCurrent(); // Data e hora da conclusão
            $table->timestamps();

            // Evita que o mesmo usuário conclua a mesma aula mais de uma vez
            $table->unique(['id_usuario', 'id_aula']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progresso');
    }
};
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
        Schema::create('certificados', function (Blueprint $table) {
            $table->id(); // Garante o bigint unsigned primário que vimos no banco
            
            // Relacionamentos corretos com suas tabelas personalizadas
            $table->foreignId('id_usuario')
                  ->constrained('usuario', 'id_usuario')
                  ->onDelete('cascade');

            $table->foreignId('id_curso')
                  ->constrained('cursos', 'id_curso')
                  ->onDelete('cascade');

            $table->string('codigo_autenticacao')->unique();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};
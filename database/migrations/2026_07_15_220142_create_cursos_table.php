<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id('id_curso');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('categoria');
            $table->string('instrutor')->nullable();
            $table->integer('carga_horaria')->nullable();
            $table->string('capa')->nullable();
            $table->string('status')->default('Não iniciado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};

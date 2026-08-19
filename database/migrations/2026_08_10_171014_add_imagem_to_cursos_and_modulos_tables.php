<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // 'capa' já existe. Adiciona a imagem de fundo do curso.
            $table->string('fundo')->nullable()->after('capa');
        });

        Schema::table('modulos', function (Blueprint $table) {
            $table->string('capa')->nullable()->after('descricao');
            $table->string('fundo')->nullable()->after('capa');
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn('fundo');
        });

        Schema::table('modulos', function (Blueprint $table) {
            $table->dropColumn(['capa', 'fundo']);
        });
    }
};
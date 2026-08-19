<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cpf')->nullable();
            $table->enum('perfil', ['administrador', 'colaborador'])->default('colaborador');
            $table->string('cargo')->nullable();
            $table->string('area')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};

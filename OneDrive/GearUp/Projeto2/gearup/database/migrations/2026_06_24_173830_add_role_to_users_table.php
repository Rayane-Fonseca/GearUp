<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['collaborator', 'manager', 'admin'])->default('collaborator')->after('email');
            $table->string('position')->nullable()->after('role');
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete()->after('position');
            $table->string('avatar')->nullable()->after('area_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn(['role', 'position', 'area_id', 'avatar']);
        });
    }
};
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
        Schema::create('clientes', function (Blueprint $table) {
            $table->uuid('id_cliente')->primary();
            $table->string('nombre');
            $table->string('usuario')->unique()->nullable();
            $table->string('password_hash');
            $table->string('telefono', 50)->nullable();
            $table->string('domicilio')->nullable();
            $table->string('correo')->unique()->nullable();
            $table->uuid('auth_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

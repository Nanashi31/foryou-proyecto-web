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
        Schema::create('visitas', function (Blueprint $table) {
            $table->id('id_visita');
            $table->foreignId('id_empleado')->references('id_empleado')->on('empleados')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_solicitud')->references('id_solicitud')->on('solicitudes')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('fecha')->useCurrent();
            $table->string('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};

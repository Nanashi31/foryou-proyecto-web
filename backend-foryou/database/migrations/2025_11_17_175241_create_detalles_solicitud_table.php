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
        Schema::create('detalles_solicitud', function (Blueprint $table) {
            $table->id('id_detalles');
            $table->foreignId('id_solicitud')->references('id_solicitud')->on('solicitudes')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('med_alt', 10, 2)->nullable();
            $table->decimal('med_anc', 10, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_solicitud');
    }
};

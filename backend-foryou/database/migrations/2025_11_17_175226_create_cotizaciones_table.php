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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id('id_cotizacion');
            $table->foreignId('id_solicitud')->references('id_solicitud')->on('solicitudes')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('fecha_cot')->useCurrent();
            $table->decimal('costo_total', 10, 2)->default(0.00);
            $table->string('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};

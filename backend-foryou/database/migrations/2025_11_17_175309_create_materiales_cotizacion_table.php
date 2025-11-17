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
        Schema::create('materiales_cotizacion', function (Blueprint $table) {
            $table->foreignId('id_mat')->references('id_material')->on('materiales')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('id_cot')->references('id_cotizacion')->on('cotizaciones')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('cant_usa', 10, 2)->default(0.00);
            $table->decimal('costo_unitario', 10, 2)->nullable();
            $table->primary(['id_mat', 'id_cot']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiales_cotizacion');
    }
};

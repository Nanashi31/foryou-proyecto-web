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
        Schema::create('material_proyecto', function (Blueprint $table) {
            $table->foreignId('id_proyecto')->references('id_proyecto')->on('proyectos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_material')->references('id_material')->on('materiales')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('cant_usada', 10, 2)->default(0.00);
            $table->primary(['id_proyecto', 'id_material']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_proyecto');
    }
};

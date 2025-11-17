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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->string('direccion');
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->uuid('id_cliente')->nullable();
            $table->text('dias_disponibles')->nullable();
            $table->timestamp('fecha_cita')->nullable();
            $table->text('materiales')->nullable();
            $table->text('tipo_proyecto')->nullable();
            $table->timestamps();

            $table->foreign('id_cliente')
                  ->references('id_cliente')
                  ->on('clientes')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};

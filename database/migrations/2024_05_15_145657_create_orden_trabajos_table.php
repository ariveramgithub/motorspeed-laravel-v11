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
        Schema::create('orden_trabajos', function (Blueprint $table) {
            $table->id();
            $table->string('cliente_rut');
            $table->string('cliente_nombre');
            $table->string('vehiculo_patente');
            $table->string('vehiculo_marca');
            $table->string('vehiculo_modelo');
            $table->string('vehiculo_version');
            $table->string('vehiculo_color');
            $table->string('vehiculo_year');
            $table->string('vehiculo_kilometraje');
            $table->string('vehiculo_transmision');
            $table->string('vehiculo_combustible');
            $table->json('servicios')->nullable();
            $table->text('detalle_cliente')->nullable();
            $table->text('detalle_taller')->nullable();
            $table->integer('valor');
            $table->timestamp('inicio');
            $table->timestamp('termino');
            $table->string('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_trabajos');
    }
};

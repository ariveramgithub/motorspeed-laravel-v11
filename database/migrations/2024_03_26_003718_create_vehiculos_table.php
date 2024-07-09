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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('patente', length: 6)->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->string('version');
            $table->string('color');
            $table->integer('year');
            $table->integer('kilometraje');
            $table->enum('transmision', ['mecanica', 'automatica']);
            $table->enum('combustible', ['gasolina', 'diesel', 'electrico', 'hibrido']);
            $table->foreignId('cliente_id')->nullable()->constrained(
                table: 'clientes'
            );
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};

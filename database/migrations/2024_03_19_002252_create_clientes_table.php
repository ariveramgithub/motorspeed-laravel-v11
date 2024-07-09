<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Cliente;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('rut', length: 10)->unique();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('email')->unique();
            $table->string('telefono1', length: 20);
            $table->string('telefono2', length: 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Cliente::create(
            [
                'rut' => '11111111-1',
                'nombre' => 'Cliente DEMO',
                'direccion' => 'Av. Libertador #102',
                'email' => 'demo@a.cl',
                'telefono1' => 911111111,
                'telefono2' => 211111111,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

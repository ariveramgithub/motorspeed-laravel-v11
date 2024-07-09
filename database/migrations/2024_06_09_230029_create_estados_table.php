<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Estado;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->timestamps();
        });
    
        Estado::create(['estado' => 'En espera']);
        Estado::create(['estado' => 'En curso']);
        Estado::create(['estado' => 'Finalizada']);
        Estado::create(['estado' => 'Entregada']);
        Estado::create(['estado' => 'Rechazada por cliente']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};

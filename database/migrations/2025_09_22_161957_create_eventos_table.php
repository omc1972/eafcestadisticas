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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('minuto');
            $table->foreignId('tipo_evento_id')->constrained('tipo_eventos');
            $table->foreignId('equipo_id')->constrained('equipos');
            $table->foreignId('jugador_id')->constrained('jugadors');
            $table->foreignId('partido_id')->constrained('partidos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};

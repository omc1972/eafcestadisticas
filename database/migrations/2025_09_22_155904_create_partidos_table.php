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
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->string('dificultad');
            $table->foreignId('equipo_casa_id')->constrained('equipos');
            $table->foreignId('equipo_fuera_id')->constrained('equipos');
            $table->foreignId('temporada_id')->constrained('temporadas');
            $table->foreignId('competicion_id')->constrained('competicions');
            $table->string('jornada');
            $table->unsignedInteger('minutos_jugados')->default(0);
            $table->unsignedInteger('puntuacion')->default(0);
            $table->unsignedInteger('posesion_casa')->default(0);
            $table->unsignedInteger('posesion_fuera')->default(0);
            $table->unsignedInteger('tiros_casa')->default(0);
            $table->unsignedInteger('tiros_fuera')->default(0);
            $table->unsignedInteger('tiros_a_puerta_casa')->default(0);
            $table->unsignedInteger('tiros_a_puerta_fuera')->default(0);
            $table->unsignedInteger('pases_casa')->default(0);
            $table->unsignedInteger('pases_fuera')->default(0);
            $table->unsignedInteger('porcentaje_pases_casa')->default(0);
            $table->unsignedInteger('porcentaje_pases_fuera')->default(0);
            $table->unsignedInteger('goles_casa')->default(0);
            $table->unsignedInteger('goles_fuera')->default(0);
            $table->unsignedInteger('entradas_casa')->default(0);
            $table->unsignedInteger('entradas_fuera')->default(0);
            $table->unsignedInteger('entradas_casa_completadas')->default(0);
            $table->unsignedInteger('entradas_fuera_completadas')->default(0);
            $table->unsignedInteger('distancia_casa')->default(0);
            $table->unsignedInteger('distancia_fuera')->default(0);
            $table->unsignedInteger('faltas_casa')->default(0);
            $table->unsignedInteger('faltas_fuera')->default(0);
            $table->unsignedInteger('penalties_casa')->default(0);
            $table->unsignedInteger('penalties_fuera')->default(0);
            $table->unsignedInteger('tarjetas_amarillas_casa')->default(0);
            $table->unsignedInteger('tarjetas_amarillas_fuera')->default(0);
            $table->unsignedInteger('tarjetas_rojas_casa')->default(0);
            $table->unsignedInteger('tarjetas_rojas_fuera')->default(0);
            $table->unsignedInteger('corners_casa')->default(0);
            $table->unsignedInteger('corners_fuera')->default(0);
            $table->unsignedInteger('interceciones_casa')->default(0);
            $table->unsignedInteger('intercepciones_fuera')->default(0);
            $table->unsignedInteger('balones_ganados_casa')->default(0);
            $table->unsignedInteger('balones_ganados_fuera')->default(0);
            $table->unsignedInteger('balones_perdidos_casa')->default(0);
            $table->unsignedInteger('balones_perdidos_fuera')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};

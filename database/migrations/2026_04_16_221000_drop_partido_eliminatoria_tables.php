<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('evento_eliminatorias');
        Schema::dropIfExists('alineacion_eliminatorias');
        Schema::dropIfExists('partido_eliminatorias');
    }

    public function down(): void
    {
        Schema::create('partido_eliminatorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eliminatoria_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('equipo_local_id')->nullable();
            $table->unsignedBigInteger('equipo_visitante_id')->nullable();
            $table->unsignedBigInteger('temporada_id')->nullable();
            $table->unsignedInteger('orden')->default(1);
            $table->timestamps();
        });

        Schema::create('alineacion_eliminatorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_eliminatoria_id')->constrained('partido_eliminatorias')->cascadeOnDelete();
            $table->foreignId('jugador_id')->constrained('jugadors')->cascadeOnDelete();
            $table->unsignedBigInteger('equipo_id')->nullable();
            $table->unsignedInteger('tiros')->default(0);
            $table->unsignedInteger('tiros_a_puerta')->default(0);
            $table->unsignedInteger('tiros_al_palo')->default(0);
            $table->unsignedInteger('pases')->default(0);
            $table->unsignedInteger('pases_exitosos')->default(0);
            $table->unsignedInteger('entradas')->default(0);
            $table->unsignedInteger('entradas_exitosas')->default(0);
            $table->unsignedInteger('regates')->default(0);
            $table->unsignedInteger('regates_exitosos')->default(0);
            $table->unsignedInteger('posesion_ganada')->default(0);
            $table->unsignedInteger('posesion_perdida')->default(0);
            $table->unsignedInteger('fueras_de_juego')->default(0);
            $table->unsignedInteger('faltas_cometidas')->default(0);
            $table->unsignedInteger('faltas_recibidas')->default(0);
            $table->unsignedInteger('posesion')->default(0);
            $table->float('distancia_recorrida')->default(0);
            $table->float('rendimiento')->default(0);
            $table->boolean('jugador_del_partido')->default(false);
            $table->timestamps();
        });

        Schema::create('evento_eliminatorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_eliminatoria_id')->constrained('partido_eliminatorias')->cascadeOnDelete();
            $table->foreignId('jugador_id')->constrained('jugadors');
            $table->unsignedBigInteger('equipo_id')->nullable();
            $table->unsignedInteger('minuto');
            $table->foreignId('tipo_evento_id')->constrained('tipo_eventos');
            $table->timestamps();
        });
    }
};

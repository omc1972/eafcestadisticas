<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partido_eliminatorias', function (Blueprint $table) {
            $table->string('dificultad')->nullable()->after('equipo_visitante_id');
            $table->foreignId('temporada_id')->nullable()->after('dificultad')->constrained('temporadas')->nullOnDelete();
            $table->string('jornada')->nullable()->after('temporada_id');
            $table->unsignedInteger('minutos_jugados')->nullable()->default(0)->after('jornada');
            $table->decimal('puntuacion', 8, 2)->nullable()->default(0)->after('minutos_jugados');
            $table->decimal('posesion_local', 8, 2)->nullable()->default(0)->after('puntuacion');
            $table->decimal('posesion_visitante', 8, 2)->nullable()->default(0)->after('posesion_local');
            $table->decimal('delantera_local', 8, 2)->nullable()->default(0)->after('posesion_visitante');
            $table->decimal('delantera_visitante', 8, 2)->nullable()->default(0)->after('delantera_local');
            $table->decimal('media_local', 8, 2)->nullable()->default(0)->after('delantera_visitante');
            $table->decimal('media_visitante', 8, 2)->nullable()->default(0)->after('media_local');
            $table->decimal('defensa_local', 8, 2)->nullable()->default(0)->after('media_visitante');
            $table->decimal('defensa_visitante', 8, 2)->nullable()->default(0)->after('defensa_local');
            $table->unsignedInteger('tiros_local')->nullable()->default(0)->after('defensa_visitante');
            $table->unsignedInteger('tiros_visitante')->nullable()->default(0)->after('tiros_local');
            $table->unsignedInteger('tiros_a_puerta_local')->nullable()->default(0)->after('tiros_visitante');
            $table->unsignedInteger('tiros_a_puerta_visitante')->nullable()->default(0)->after('tiros_a_puerta_local');
            $table->unsignedInteger('pases_local')->nullable()->default(0)->after('tiros_a_puerta_visitante');
            $table->unsignedInteger('pases_visitante')->nullable()->default(0)->after('pases_local');
            $table->decimal('porcentaje_pases_local', 8, 2)->nullable()->default(0)->after('pases_visitante');
            $table->decimal('porcentaje_pases_visitante', 8, 2)->nullable()->default(0)->after('porcentaje_pases_local');
            $table->unsignedInteger('valor_local')->nullable()->default(0)->after('porcentaje_pases_visitante');
            $table->unsignedInteger('valor_visitante')->nullable()->default(0)->after('valor_local');
            $table->unsignedInteger('entradas_local')->nullable()->default(0)->after('valor_visitante');
            $table->unsignedInteger('entradas_visitante')->nullable()->default(0)->after('entradas_local');
            $table->unsignedInteger('entradas_local_completadas')->nullable()->default(0)->after('entradas_visitante');
            $table->unsignedInteger('entradas_visitante_completadas')->nullable()->default(0)->after('entradas_local_completadas');
            $table->decimal('distancia_local', 8, 2)->nullable()->default(0)->after('entradas_visitante_completadas');
            $table->decimal('distancia_visitante', 8, 2)->nullable()->default(0)->after('distancia_local');
            $table->unsignedInteger('faltas_local')->nullable()->default(0)->after('distancia_visitante');
            $table->unsignedInteger('faltas_visitante')->nullable()->default(0)->after('faltas_local');
            $table->unsignedInteger('penalties_local')->nullable()->default(0)->after('faltas_visitante');
            $table->unsignedInteger('penalties_visitante')->nullable()->default(0)->after('penalties_local');
            $table->unsignedInteger('tarjetas_amarillas_local')->nullable()->default(0)->after('penalties_visitante');
            $table->unsignedInteger('tarjetas_amarillas_visitante')->nullable()->default(0)->after('tarjetas_amarillas_local');
            $table->unsignedInteger('tarjetas_rojas_local')->nullable()->default(0)->after('tarjetas_amarillas_visitante');
            $table->unsignedInteger('tarjetas_rojas_visitante')->nullable()->default(0)->after('tarjetas_rojas_local');
            $table->unsignedInteger('corners_local')->nullable()->default(0)->after('tarjetas_rojas_visitante');
            $table->unsignedInteger('corners_visitante')->nullable()->default(0)->after('corners_local');
            $table->unsignedInteger('intercepciones_local')->nullable()->default(0)->after('corners_visitante');
            $table->unsignedInteger('intercepciones_visitante')->nullable()->default(0)->after('intercepciones_local');
            $table->unsignedInteger('balones_ganados_local')->nullable()->default(0)->after('intercepciones_visitante');
            $table->unsignedInteger('balones_ganados_visitante')->nullable()->default(0)->after('balones_ganados_local');
            $table->unsignedInteger('balones_perdidos_local')->nullable()->default(0)->after('balones_ganados_visitante');
            $table->unsignedInteger('balones_perdidos_visitante')->nullable()->default(0)->after('balones_perdidos_local');
        });

        Schema::create('alineacion_eliminatorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_eliminatoria_id')->constrained('partido_eliminatorias')->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->foreignId('jugador_id')->constrained('jugadors')->cascadeOnDelete();
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
            $table->decimal('posesion', 8, 2)->default(0);
            $table->decimal('distancia_recorrida', 8, 2)->default(0);
            $table->decimal('rendimiento', 8, 2)->default(0);
            $table->boolean('jugador_del_partido')->default(false);
            $table->timestamps();
        });

        Schema::create('evento_eliminatorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('minuto');
            $table->foreignId('tipo_evento_id')->constrained('tipo_eventos')->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->foreignId('jugador_id')->constrained('jugadors')->cascadeOnDelete();
            $table->foreignId('partido_eliminatoria_id')->constrained('partido_eliminatorias')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_eliminatorias');
        Schema::dropIfExists('alineacion_eliminatorias');

        Schema::table('partido_eliminatorias', function (Blueprint $table) {
            $table->dropConstrainedForeignId('temporada_id');
            $table->dropColumn([
                'dificultad',
                'jornada',
                'minutos_jugados',
                'puntuacion',
                'posesion_local',
                'posesion_visitante',
                'delantera_local',
                'delantera_visitante',
                'media_local',
                'media_visitante',
                'defensa_local',
                'defensa_visitante',
                'tiros_local',
                'tiros_visitante',
                'tiros_a_puerta_local',
                'tiros_a_puerta_visitante',
                'pases_local',
                'pases_visitante',
                'porcentaje_pases_local',
                'porcentaje_pases_visitante',
                'valor_local',
                'valor_visitante',
                'entradas_local',
                'entradas_visitante',
                'entradas_local_completadas',
                'entradas_visitante_completadas',
                'distancia_local',
                'distancia_visitante',
                'faltas_local',
                'faltas_visitante',
                'penalties_local',
                'penalties_visitante',
                'tarjetas_amarillas_local',
                'tarjetas_amarillas_visitante',
                'tarjetas_rojas_local',
                'tarjetas_rojas_visitante',
                'corners_local',
                'corners_visitante',
                'intercepciones_local',
                'intercepciones_visitante',
                'balones_ganados_local',
                'balones_ganados_visitante',
                'balones_perdidos_local',
                'balones_perdidos_visitante',
            ]);
        });
    }
};

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
        Schema::create('alineacions', function (Blueprint $table) {
            $table->id();
            $table->string('partido_id');
            $table->unsignedInteger('jugador_id');
            $table->unsignedInteger('tiros');
            $table->unsignedInteger('tiros_a_puerta');
            $table->unsignedInteger('tiros_al_palo')->default(0);
            $table->unsignedInteger('pases');
            $table->unsignedInteger('pases_exitosos');
            $table->unsignedInteger('entradas');
            $table->unsignedInteger('entradas_exitosas');
            $table->unsignedInteger('regates');
            $table->unsignedInteger('regates_exitosos');
            $table->unsignedInteger('posesion_ganada');
            $table->unsignedInteger('posesion_perdida');
            $table->unsignedInteger('faltas_cometidas');
            $table->unsignedInteger('faltas_recibidas');
            $table->unsignedInteger('fueras_de_juego');
            $table->unsignedInteger('distancia_recorrida');
            $table->unsignedInteger('posesion');
            $table->float('rendimiento');
            $table->float('jugador_del_partido');   
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alineacions');
    }
};

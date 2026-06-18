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
        Schema::create('jugadors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('posicion');
            $table->string('pais');
            $table->string('altura');
            $table->string('peso');
            $table->dateTime('fecha_nacimiento');
            $table->unsignedInteger('ritmo');
            $table->unsignedInteger('aceleracion');
            $table->unsignedInteger('velocidad');
            $table->unsignedInteger('tiro');
            $table->unsignedInteger('pos_ataque');
            $table->unsignedInteger('finalizacion');
            $table->unsignedInteger('potencia_de_tiro');
            $table->unsignedInteger('tiro_lejano');
            $table->unsignedInteger('voleas');
            $table->unsignedInteger('penalties');
            $table->unsignedInteger('pase');
            $table->unsignedInteger('vision');
            $table->unsignedInteger('centros');
            $table->unsignedInteger('precision_falta');
            $table->unsignedInteger('pase_corto');
            $table->unsignedInteger('pase_largo');
            $table->unsignedInteger('efecto');
            $table->unsignedInteger('regate');
            $table->unsignedInteger('agilidad');
            $table->unsignedInteger('equilibrio');
            $table->unsignedInteger('anticipacion');
            $table->unsignedInteger('control_de_balon');
            $table->unsignedInteger('regates');
            $table->unsignedInteger('compostura');
            $table->unsignedInteger('defensa');
            $table->unsignedInteger('intercepciones');
            $table->unsignedInteger('precision_cabezazo');
            $table->unsignedInteger('capacidad_defensiva');
            $table->unsignedInteger('robos');
            $table->unsignedInteger('entradas');
            $table->unsignedInteger('fisico');
            $table->unsignedInteger('salto');
            $table->unsignedInteger('resistencia');
            $table->unsignedInteger('fuerza');
            $table->unsignedInteger('agresividad');
            $table->unsignedInteger('media');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadors');
    }
};

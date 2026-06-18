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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipacion_id')->constrained('equipacions');
            $table->foreignId('entrenador_id')->constrained('entrenadors');
            $table->foreignId('estadio_id')->constrained('estadios');
            $table->string('nombre');
            $table->string('usuario');
            $table->string('codigo');
            $table->unsignedInteger('quimica')->default(0);
            $table->unsignedInteger('valoracion')->default(0);
            $table->string('tactica');
            $table->string('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};

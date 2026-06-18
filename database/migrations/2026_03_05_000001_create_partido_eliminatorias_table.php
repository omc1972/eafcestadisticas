<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partido_eliminatorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eliminatoria_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipo_local_id')->nullable()->constrained('equipos')->onDelete('cascade');
            $table->foreignId('equipo_visitante_id')->nullable()->constrained('equipos')->onDelete('cascade');
            $table->integer('goles_local')->nullable();
            $table->integer('goles_visitante')->nullable();
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partido_eliminatorias');
    }
};

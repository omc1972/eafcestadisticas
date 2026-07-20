<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('division_rivales', function (Blueprint $table) {
            $table->dropForeign(['rival_id']);
            $table->dropColumn('rival_id');
            $table->dropForeign(['partido_id']);
            $table->dropColumn('partido_id');
            $table->dropColumn('jugado');
            $table->foreignId('equipo_id')->after('division_id')->constrained('equipos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('division_rivales', function (Blueprint $table) {
            $table->dropForeign(['equipo_id']);
            $table->dropColumn('equipo_id');
            $table->foreignId('rival_id')->constrained('rivals')->cascadeOnDelete();
            $table->boolean('jugado')->default(false);
            $table->foreignId('partido_id')->nullable()->constrained('partidos')->nullOnDelete();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisiones', function (Blueprint $table) {
            // Agregar columna rivals_battle_id si no existe
            if (!Schema::hasColumn('divisiones', 'rivals_battle_id')) {
                $table->foreignId('rivals_battle_id')->nullable()->after('squad_battle_id')->constrained('rivals_battles')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('divisiones', function (Blueprint $table) {
            if (Schema::hasColumn('divisiones', 'rivals_battle_id')) {
                $table->dropForeign(['rivals_battle_id']);
                $table->dropColumn('rivals_battle_id');
            }
        });
    }
};

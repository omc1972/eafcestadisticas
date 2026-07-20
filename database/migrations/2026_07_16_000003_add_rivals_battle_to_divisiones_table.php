<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisiones', function (Blueprint $table) {
            // Hacer squad_battle_id nullable
            $table->foreignId('squad_battle_id')->nullable()->change();
            
            // Agregar rivals_battle_id
            $table->unsignedBigInteger('rivals_battle_id')->nullable()->after('squad_battle_id');
            $table->foreign('rivals_battle_id')->references('id')->on('rivals_battles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('divisiones', function (Blueprint $table) {
            $table->dropForeign(['rivals_battle_id']);
            $table->dropColumn('rivals_battle_id');
            
            // Revertir squad_battle_id a NOT NULL
            $table->foreignId('squad_battle_id')->change();
        });
    }
};

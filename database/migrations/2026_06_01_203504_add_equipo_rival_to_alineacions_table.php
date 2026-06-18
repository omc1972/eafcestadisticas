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
        Schema::table('alineacions', function (Blueprint $table) {
            if (!Schema::hasColumn('alineacions', 'equipo_id')) {
                $table->unsignedBigInteger('equipo_id')->nullable()->after('jugador_id');
            }
            if (!Schema::hasColumn('alineacions', 'rival_id')) {
                $table->unsignedBigInteger('rival_id')->nullable()->after('equipo_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alineacions', function (Blueprint $table) {
            if (Schema::hasColumn('alineacions', 'rival_id')) {
                $table->dropColumn('rival_id');
            }
            if (Schema::hasColumn('alineacions', 'equipo_id')) {
                $table->dropColumn('equipo_id');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            if (!Schema::hasColumn('partidos', 'visitante_id')) {
                $table->unsignedBigInteger('visitante_id')->nullable()->after('equipo_id');
            }

            if (!Schema::hasColumn('partidos', 'visitante_es_rival')) {
                $table->boolean('visitante_es_rival')->default(true)->after('visitante_id');
            }
        });

        if (Schema::hasColumn('partidos', 'rival_id') && Schema::hasColumn('partidos', 'visitante_id')) {
            DB::table('partidos')
                ->whereNull('visitante_id')
                ->update([
                    'visitante_id' => DB::raw('rival_id'),
                    'visitante_es_rival' => true,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            if (Schema::hasColumn('partidos', 'visitante_es_rival')) {
                $table->dropColumn('visitante_es_rival');
            }

            if (Schema::hasColumn('partidos', 'visitante_id')) {
                $table->dropColumn('visitante_id');
            }
        });
    }
};

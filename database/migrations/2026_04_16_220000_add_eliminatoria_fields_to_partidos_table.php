<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            if (!Schema::hasColumn('partidos', 'eliminatoria_id')) {
                $table->foreignId('eliminatoria_id')
                    ->nullable()
                    ->after('campeonato_id')
                    ->constrained('eliminatorias')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('partidos', 'orden_eliminatoria')) {
                $table->unsignedInteger('orden_eliminatoria')
                    ->nullable()
                    ->after('eliminatoria_id');
            }

            $table->index(['campeonato_id', 'temporada_id', 'eliminatoria_id', 'orden_eliminatoria'], 'partidos_eliminatoria_round_idx');
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropIndex('partidos_eliminatoria_round_idx');

            if (Schema::hasColumn('partidos', 'eliminatoria_id')) {
                $table->dropConstrainedForeignId('eliminatoria_id');
            }

            if (Schema::hasColumn('partidos', 'orden_eliminatoria')) {
                $table->dropColumn('orden_eliminatoria');
            }
        });
    }
};

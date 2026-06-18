<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            if (!Schema::hasColumn('eventos', 'local_id')) {
                $table->unsignedBigInteger('local_id')->nullable()->after('partido_id');
            }

            if (!Schema::hasColumn('eventos', 'visitante_id')) {
                $table->unsignedBigInteger('visitante_id')->nullable()->after('local_id');
            }
        });

        DB::table('eventos')
            ->whereNull('local_id')
            ->update([
                'local_id' => DB::raw('equipo_id'),
            ]);

        if (Schema::hasColumn('eventos', 'rival_id')) {
            DB::table('eventos')
                ->whereNull('visitante_id')
                ->update([
                    'visitante_id' => DB::raw('rival_id'),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            if (Schema::hasColumn('eventos', 'visitante_id')) {
                $table->dropColumn('visitante_id');
            }

            if (Schema::hasColumn('eventos', 'local_id')) {
                $table->dropColumn('local_id');
            }
        });
    }
};

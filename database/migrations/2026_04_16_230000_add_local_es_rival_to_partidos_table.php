<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            if (!Schema::hasColumn('partidos', 'local_es_rival')) {
                $table->boolean('local_es_rival')->default(false)->after('equipo_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            if (Schema::hasColumn('partidos', 'local_es_rival')) {
                $table->dropColumn('local_es_rival');
            }
        });
    }
};

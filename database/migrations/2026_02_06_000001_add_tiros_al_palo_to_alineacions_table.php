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
            $table->unsignedInteger('tiros_al_palo')->default(0)->after('tiros_a_puerta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alineacions', function (Blueprint $table) {
            $table->dropColumn('tiros_al_palo');
        });
    }
};

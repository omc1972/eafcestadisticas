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
        Schema::table('ligas', function (Blueprint $table) {
            $table->dropForeign(['competicion_id']);
            $table->dropColumn(['competicion_id', 'activa']);
            $table->string('pais')->nullable()->after('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ligas', function (Blueprint $table) {
            $table->dropColumn('pais');
            $table->foreignId('competicion_id')->nullable()->constrained('competicions')->onDelete('set null');
            $table->boolean('activa')->default(true);
        });
    }
};

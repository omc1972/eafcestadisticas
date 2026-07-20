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
        Schema::table('divisiones', function (Blueprint $table) {
            $table->unsignedBigInteger('rival_id')->nullable()->after('squad_battle_id');
            $table->foreign('rival_id')->references('id')->on('rivals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('divisiones', function (Blueprint $table) {
            $table->dropForeign(['rival_id']);
            $table->dropColumn('rival_id');
        });
    }
};

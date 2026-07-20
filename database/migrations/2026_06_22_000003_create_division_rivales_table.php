<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('division_rivales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisiones')->cascadeOnDelete();
            $table->foreignId('rival_id')->constrained('rivals')->cascadeOnDelete();
            $table->unsignedTinyInteger('orden')->default(1);
            $table->boolean('jugado')->default(false);
            $table->foreignId('partido_id')->nullable()->constrained('partidos')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('division_rivales');
    }
};

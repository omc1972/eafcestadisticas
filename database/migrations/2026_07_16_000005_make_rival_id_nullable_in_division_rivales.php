<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hacer rival_id nullable
        DB::statement('ALTER TABLE division_rivales MODIFY rival_id bigint unsigned NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE division_rivales MODIFY rival_id bigint unsigned NOT NULL');
    }
};

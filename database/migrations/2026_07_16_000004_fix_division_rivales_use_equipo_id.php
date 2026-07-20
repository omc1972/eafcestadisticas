<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar foreign key para equipo_id usando SQL raw
        DB::statement('ALTER TABLE division_rivales ADD CONSTRAINT division_rivales_equipo_id_foreign FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE division_rivales DROP FOREIGN KEY division_rivales_equipo_id_foreign');
    }
};

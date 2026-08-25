<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE amenities 
            ALTER COLUMN name TYPE jsonb 
            USING CASE 
                WHEN name::text ~ '^\s*\{.*\}\s*$' THEN name::jsonb 
                ELSE jsonb_build_object('az', name) 
            END
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE amenities 
            ALTER COLUMN name TYPE varchar(255) 
            USING COALESCE(name->>'az', name->>'tr', name->>'en', name->>'ru', '')
        ");
    }
};

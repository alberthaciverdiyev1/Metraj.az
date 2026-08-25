<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE properties 
            ALTER COLUMN title TYPE jsonb 
            USING CASE 
                WHEN title::text ~ '^\s*\{.*\}\s*$' THEN title::jsonb 
                ELSE jsonb_build_object('az', title, 'tr', title, 'en', title, 'ru', title) 
            END
        ");

        DB::statement("
            ALTER TABLE properties 
            ALTER COLUMN description TYPE jsonb 
            USING CASE 
                WHEN description IS NULL THEN NULL
                WHEN description::text ~ '^\s*\{.*\}\s*$' THEN description::jsonb 
                ELSE jsonb_build_object('az', description, 'tr', description, 'en', description, 'ru', description) 
            END
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE properties 
            ALTER COLUMN title TYPE varchar(255) 
            USING COALESCE(title->>'az', title->>'tr', title->>'en', title->>'ru', '')
        ");

        DB::statement("
            ALTER TABLE properties 
            ALTER COLUMN description TYPE text 
            USING COALESCE(description->>'az', description->>'tr', description->>'en', description->>'ru', '')
        ");
    }
};

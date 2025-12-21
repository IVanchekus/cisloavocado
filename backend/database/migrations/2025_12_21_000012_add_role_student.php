<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('roles')->where('slug', 'student')->exists();
        if ($exists) {
            return;
        }

        DB::table('roles')->insert([
            'slug' => 'student',
            'title' => json_encode([
                'ru' => 'Ученик',
                'en' => 'Student',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('roles')->where('slug', 'student')->delete();
    }
};



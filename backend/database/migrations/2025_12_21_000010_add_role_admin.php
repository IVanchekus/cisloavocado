<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('roles')->where('slug', 'admin')->exists();
        if ($exists) {
            return;
        }

        DB::table('roles')->insert([
            'slug' => 'admin',
            'title' => json_encode([
                'ru' => 'Админ',
                'en' => 'Admin',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('roles')->where('slug', 'admin')->delete();
    }
};



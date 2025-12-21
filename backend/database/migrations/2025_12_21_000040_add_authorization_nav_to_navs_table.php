<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('navs')->where('name', 'authorization')->exists();
        if ($exists) {
            return;
        }

        DB::table('navs')->insert([
            'label' => json_encode([
                'en' => 'Authorization',
                'ru' => 'Авторизация',
            ]),
            'name' => 'authorization',
            'component' => 'Authorization',
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        DB::table('navs')->where('name', 'authorization')->delete();
    }
};



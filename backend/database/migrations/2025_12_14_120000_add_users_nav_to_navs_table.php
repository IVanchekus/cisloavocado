<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('navs')->where('name', 'users')->exists();
        if ($exists) {
            return;
        }

        DB::table('navs')->insert([
            'label' => json_encode([
                'en' => 'Users',
                'ru' => 'Пользователи',
            ]),
            'name' => 'users',
            'component' => 'Users',
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        DB::table('navs')->where('name', 'users')->delete();
    }
};



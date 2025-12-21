<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            [
                'slug' => 'users.view',
                'title' => json_encode(['ru' => 'Просмотр пользователей', 'en' => 'View users']),
            ],
            [
                'slug' => 'users.manage',
                'title' => json_encode(['ru' => 'Управление пользователями', 'en' => 'Manage users']),
            ],
            [
                'slug' => 'roles.manage',
                'title' => json_encode(['ru' => 'Управление ролями', 'en' => 'Manage roles']),
            ],
            [
                'slug' => 'exercises.create',
                'title' => json_encode(['ru' => 'Создание заданий', 'en' => 'Create exercises']),
            ],
        ];

        foreach ($permissions as $p) {
            $exists = DB::table('permissions')->where('slug', $p['slug'])->exists();
            if ($exists) {
                continue;
            }
            DB::table('permissions')->insert([
                'slug' => $p['slug'],
                'title' => $p['title'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('permissions')->whereIn('slug', [
            'users.view',
            'users.manage',
            'roles.manage',
            'exercises.create',
        ])->delete();
    }
};



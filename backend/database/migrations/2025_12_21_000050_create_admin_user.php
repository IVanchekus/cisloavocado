<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        if (!$adminRoleId) {
            return;
        }

        // Создаём/обновляем системного админа
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@mail.ru'],
            [
                'name' => 'Админ',
                'password' => bcrypt('admin'),
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );

        $adminUserId = DB::table('users')->where('email', 'admin@mail.ru')->value('id');
        if (!$adminUserId) {
            return;
        }

        // Выдаём роль admin, но не падаем при повторной миграции/сидинге.
        DB::table('role_user')->insertOrIgnore([
            'user_id' => $adminUserId,
            'role_id' => $adminRoleId,
        ]);
    }

    public function down(): void
    {
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        $adminUserId = DB::table('users')->where('email', 'admin@mail.ru')->value('id');
        if ($adminRoleId && $adminUserId) {
            DB::table('role_user')
                ->where('user_id', $adminUserId)
                ->where('role_id', $adminRoleId)
                ->delete();
        }

        DB::table('users')->where('email', 'admin@mail.ru')->delete();
    }
};



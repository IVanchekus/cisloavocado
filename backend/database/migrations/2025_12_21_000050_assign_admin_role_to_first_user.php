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

        // Ранее роль admin выдавалась "первому пользователю".
        // Теперь фиксируемся на системном админе по email.
        $adminUserId = DB::table('users')->where('email', 'admin@mail.ru')->value('id');
        if (!$adminUserId) {
            return;
        }

        $exists = DB::table('role_user')
            ->where('user_id', $adminUserId)
            ->where('role_id', $adminRoleId)
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('role_user')->insert([
            'user_id' => $adminUserId,
            'role_id' => $adminRoleId,
        ]);
    }

    public function down(): void
    {
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        $adminUserId = DB::table('users')->where('email', 'admin@mail.ru')->value('id');
        if (!$adminRoleId || !$adminUserId) {
            return;
        }

        DB::table('role_user')
            ->where('user_id', $adminUserId)
            ->where('role_id', $adminRoleId)
            ->delete();
    }
};



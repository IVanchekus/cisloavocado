<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = 'admin@mail.ru';
        $adminPassword = 'admin';

        $adminRole = Role::query()->where('slug', 'admin')->first();
        if (!$adminRole) {
            // Роли создаются миграциями, но на всякий случай не падаем.
            return;
        }

        $admin = User::query()->firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Админ',
                'password' => Hash::make($adminPassword),
            ],
        );

        // Если пользователь существовал ранее — обновим пароль, чтобы он точно совпадал с требованием.
        $admin->password = Hash::make($adminPassword);
        $admin->name = $admin->name ?: 'Админ';
        $admin->save();

        // Админ имеет роль admin (и только её).
        $admin->roles()->sync([$adminRole->id]);

        // ВАЖНО: снимаем роль admin со всех остальных пользователей.
        $adminRole->users()
            ->where('users.id', '!=', $admin->id)
            ->detach();
    }
}



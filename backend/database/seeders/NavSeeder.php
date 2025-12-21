<?php

namespace Database\Seeders;

use App\Models\Nav;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $home = Nav::query()->firstOrCreate(
            ['name' => 'home'],
            [
                'label' => json_encode(['en' => 'Home', 'ru' => 'Главная']),
                'component' => 'Home',
                'is_active' => true,
                'parent_id' => null,
            ],
        );

        $informatics = Nav::query()->firstOrCreate(
            ['name' => 'informatics'],
            [
                'label' => json_encode(['en' => 'Informatics', 'ru' => 'Информатика']),
                'component' => 'Informatics',
                'is_active' => true,
                'parent_id' => null,
            ],
        );

        // Parent dropdown: "Администрирование" (без компонента)
        $admin = Nav::query()->firstOrCreate(
            ['name' => 'authorization'],
            [
                'label' => json_encode(['en' => 'Administration', 'ru' => 'Администрирование']),
                'component' => null,
                'is_active' => true,
                'parent_id' => null,
            ],
        );

        // Дочерние пункты админки (все навигации должны быть в БД).
        Nav::query()->firstOrCreate(
            ['name' => 'admin-users-roles'],
            [
                'label' => json_encode(['en' => 'Users & roles', 'ru' => 'Пользователи и роли']),
                'component' => 'AdminUsers',
                'is_active' => true,
                'parent_id' => $admin->id,
            ],
        );

        Nav::query()->firstOrCreate(
            ['name' => 'admin-roles'],
            [
                'label' => json_encode(['en' => 'Roles', 'ru' => 'Роли']),
                'component' => 'AdminRoles',
                'is_active' => true,
                'parent_id' => $admin->id,
            ],
        );

        // "Пользователи" — только внутри выпадающего списка админки.
        Nav::query()->firstOrCreate(
            ['name' => 'users'],
            [
                'label' => json_encode(['en' => 'Users', 'ru' => 'Пользователи']),
                'component' => 'Users',
                'is_active' => true,
                'parent_id' => $admin->id,
            ],
        );
    }
}

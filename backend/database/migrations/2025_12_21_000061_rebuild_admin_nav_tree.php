<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Parent: "Администрирование" (без компонента)
        $parent = DB::table('navs')->where('name', 'authorization')->first();
        if (!$parent) {
            DB::table('navs')->insert([
                'label' => json_encode([
                    'en' => 'Administration',
                    'ru' => 'Администрирование',
                ]),
                'name' => 'authorization',
                'component' => null,
                'is_active' => true,
            ]);
            $parentId = DB::getPdo()->lastInsertId();
        } else {
            $parentId = $parent->id;
            DB::table('navs')->where('id', $parentId)->update([
                'label' => $parent->label ?? json_encode(['en' => 'Administration', 'ru' => 'Администрирование']),
                'component' => null, // важно: родитель без компонента
                'is_active' => true,
                'parent_id' => null,
            ]);
        }

        // Child: "Создание пользователей и выдача ролей"
        $this->upsertChild(
            $parentId,
            'admin-users-roles',
            'AdminUsers',
            ['en' => 'Users & roles', 'ru' => 'Пользователи и роли']
        );

        // Child: "Редактирование ролей"
        $this->upsertChild(
            $parentId,
            'admin-roles',
            'AdminRoles',
            ['en' => 'Roles', 'ru' => 'Роли']
        );

        // Child: "Пользователи" (переносим существующий users под parent)
        $existsUsers = DB::table('navs')->where('name', 'users')->exists();
        if ($existsUsers) {
            DB::table('navs')->where('name', 'users')->update([
                'parent_id' => $parentId,
                'component' => 'Users',
                'is_active' => true,
            ]);
        } else {
            $this->upsertChild(
                $parentId,
                'users',
                'Users',
                ['en' => 'Users', 'ru' => 'Пользователи']
            );
        }
    }

    private function upsertChild(int $parentId, string $name, ?string $component, array $label): void
    {
        $exists = DB::table('navs')->where('name', $name)->exists();
        if ($exists) {
            DB::table('navs')->where('name', $name)->update([
                'parent_id' => $parentId,
                'component' => $component,
                'label' => json_encode($label),
                'is_active' => true,
            ]);
            return;
        }

        DB::table('navs')->insert([
            'parent_id' => $parentId,
            'label' => json_encode($label),
            'name' => $name,
            'component' => $component,
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        // Не удаляем users, только отвязываем от родителя и удаляем новые admin-страницы.
        $parentId = DB::table('navs')->where('name', 'authorization')->value('id');
        if ($parentId) {
            DB::table('navs')->whereIn('name', ['admin-users-roles', 'admin-roles'])->delete();
            DB::table('navs')->where('name', 'users')->update(['parent_id' => null]);
            // component родителя не восстанавливаем (может быть конфликт с прошлой логикой)
        }
    }
};



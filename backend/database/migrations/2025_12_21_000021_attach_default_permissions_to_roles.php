<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $roleIdsBySlug = DB::table('roles')
            ->whereIn('slug', ['admin', 'teacher', 'student'])
            ->pluck('id', 'slug');

        $permissionIdsBySlug = DB::table('permissions')
            ->whereIn('slug', ['users.view', 'users.manage', 'roles.manage', 'exercises.create'])
            ->pluck('id', 'slug');

        // Admin: all
        $this->attach($roleIdsBySlug['admin'] ?? null, array_values($permissionIdsBySlug->toArray()));

        // Teacher: create exercises
        $this->attach($roleIdsBySlug['teacher'] ?? null, [
            $permissionIdsBySlug['exercises.create'] ?? null,
        ]);

        // Student: no permissions by default
    }

    private function attach(?int $roleId, array $permissionIds): void
    {
        if (!$roleId) {
            return;
        }

        foreach ($permissionIds as $pid) {
            if (!$pid) {
                continue;
            }

            $exists = DB::table('permission_role')
                ->where('role_id', $roleId)
                ->where('permission_id', $pid)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('permission_role')->insert([
                'role_id' => $roleId,
                'permission_id' => $pid,
            ]);
        }
    }

    public function down(): void
    {
        $roleIds = DB::table('roles')
            ->whereIn('slug', ['admin', 'teacher', 'student'])
            ->pluck('id');

        if ($roleIds->isEmpty()) {
            return;
        }

        DB::table('permission_role')->whereIn('role_id', $roleIds)->delete();
    }
};



<?php

namespace App\Http\Controllers;

use App\Models\Nav;
use Illuminate\Support\Facades\Auth;

class NavController extends Controller
{
    public function getNavs()
    {
        $navs = Nav::where('is_active', true)->orderBy('id');

        // Basic RBAC for navs:
        // - users requires users.view
        // - admin users/roles requires users.manage
        // - admin roles requires roles.manage
        // - parent "authorization" показываем только если есть доступные дети
        $user = Auth::user();
        if (!$user) {
            return $navs->whereNotIn('name', ['users', 'authorization'])->get();
        }

        $user->loadMissing('roles.permissions');

        $all = $navs->get();

        $allowed = $all->filter(function ($nav) use ($user) {
            if ($nav->name === 'users') {
                return $user->hasPermission('users.view');
            }
            if ($nav->name === 'admin-users-roles') {
                return $user->hasPermission('users.manage');
            }
            if ($nav->name === 'admin-roles') {
                return $user->hasPermission('roles.manage');
            }
            // parent решим ниже
            if ($nav->name === 'authorization') {
                return true;
            }
            return true;
        })->values();

        // Если у пользователя нет ни одного доступного дочернего пункта админки — скрываем parent.
        $authId = $allowed->firstWhere('name', 'authorization')->id ?? null;
        if ($authId) {
            $hasAnyChild = $allowed->contains(fn ($n) => (int) ($n->parent_id ?? 0) === (int) $authId);
            if (!$hasAnyChild) {
                $allowed = $allowed->reject(fn ($n) => $n->name === 'authorization')->values();
            }
        }

        return $allowed;
    }
}

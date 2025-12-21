<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::query()
            ->with(['permissions:id,slug,title'])
            ->select(['id', 'slug', 'title'])
            ->orderBy('id')
            ->get();

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:roles,slug',
            'title_ru' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'permission_ids' => 'array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::create([
            'slug' => $validated['slug'],
            'title' => [
                'ru' => $validated['title_ru'],
                'en' => $validated['title_en'] ?? $validated['title_ru'],
            ],
        ]);

        if (isset($validated['permission_ids'])) {
            $role->permissions()->sync($validated['permission_ids']);
        }

        return response()->json($role->load(['permissions:id,slug,title']), 201);
    }

    public function syncPermissions(Request $request, $id)
    {
        $validated = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);
        $role->permissions()->sync($validated['permission_ids']);

        return response()->json($role->load(['permissions:id,slug,title']));
    }
}



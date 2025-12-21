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



<?php

namespace App\Http\Controllers;

use App\Repositories\Dictionaries\PermissionDictionaryRepository;
use App\Repositories\Dictionaries\RoleDictionaryRepository;

class DictionariesController extends Controller
{
    public function roles(RoleDictionaryRepository $roles)
    {
        return response()->json($roles->all());
    }

    public function permissions(PermissionDictionaryRepository $permissions)
    {
        return response()->json($permissions->all());
    }
}



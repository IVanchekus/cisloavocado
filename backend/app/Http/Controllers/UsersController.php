<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->select(['id', 'name', 'email', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }
}



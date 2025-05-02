<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function getUserData($id)
    {
        return User::find($id);
    }
}
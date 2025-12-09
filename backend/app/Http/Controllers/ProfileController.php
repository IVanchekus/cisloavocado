<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exercise;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function getUserData($id)
    {
        return User::find($id);
    }

    public function getMyExercises()
    {
        return Exercise::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
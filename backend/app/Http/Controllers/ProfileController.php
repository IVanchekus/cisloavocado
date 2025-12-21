<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exercise;
use App\Models\UserSolvedTrainingOption;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function getUserData($id)
    {
        return User::with('roles')->find($id);
    }

    public function getMyExercises()
    {
        return Exercise::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getMySolvedTrainingOptions()
    {
        $rows = UserSolvedTrainingOption::query()
            ->with([
                'trainingOption:id,hash,name',
                'verifier:id,name',
            ])
            ->where('user_id', Auth::id())
            ->where('is_solved', true)
            ->orderByDesc('finished_at')
            ->get();

        return response()->json($rows);
    }
}
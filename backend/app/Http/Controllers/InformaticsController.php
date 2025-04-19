<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class InformaticsController extends Controller
{
    public function getAllExercises() {

        $exercises = Exercise::where('subject_id', 1)
            ->where('is_public', true)
            ->where('is_approved', true)
            ->with(['user', 'category', 'level'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($exercises);
    }
}

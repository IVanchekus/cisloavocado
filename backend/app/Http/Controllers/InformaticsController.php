<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\TrainingOption;
use Illuminate\Http\Request;

class InformaticsController extends Controller
{
    public function getAllExercises() {

        $exercises = Exercise::where([
                "subject_id" => 1,
                "is_public" => true,
                "is_approved" => true,
            ])
            ->with(['user', 'category', 'level'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($exercises);
    }

    public function getTrainingOptions() {
        $trainingOptions = TrainingOption::select(['id', 'hash', 'name'])
            ->where([
                "subject_id" => 1,
                "is_public" => true,
                "is_approved" => true,
            ])
            ->whereHas('exercises');
            
        return response()->json($trainingOptions->get());
    }
}

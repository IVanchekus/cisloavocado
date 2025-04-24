<?php

namespace App\Http\Controllers;

use App\Models\TrainingOption;
use App\Models\UserSolvedExercise;
use App\Models\UserSolvedTrainingOption;
use Illuminate\Http\Request;

class TrainingOptionController extends Controller
{
    public function getExercises($hash)
    {
        $trainingOption = TrainingOption::where('hash', $hash)->first();
        if (!$trainingOption) {
            return response()->json(['message' => 'Training option not found'], 404);
        }

        $exercises = $trainingOption->exercises;
        if ($exercises->isEmpty()) {
            return response()->json(['message' => 'No exercises found'], 404);
        }

        $userSolvedTrainingOption = UserSolvedTrainingOption::firstOrNew([
            'user_id' => auth()->id(),
            'training_option_id' => $trainingOption->id,
        ]);
        
        if (!$userSolvedTrainingOption->started_at) {
            $userSolvedTrainingOption->started_at = now();
            $userSolvedTrainingOption->save();
        }

        return response()->json(
            [
                "trainingOption" => $trainingOption,
                "exercises" => $exercises,
            ]
        );
    }

    public function saveAnswers(Request $request)
    {
        $validated = $request->validate([
            'hash' => 'required|string',
            'answers' => 'required|array',
        ]);

        $trainingOption = TrainingOption::where('hash', $validated['hash'])->first();
        if (!$trainingOption) {
            return response()->json(['message' => 'Training option not found'], 404);
        }

        foreach ($validated['answers'] as $answer) {
            if (!isset($answer['exerciseId']) || !isset($answer['answer'])) {
                return response()->json(['message' => 'Invalid answer format'], 422);
            }

            UserSolvedExercise::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'exercise_id' => $answer['exerciseId'],
                ],
                [
                    'answer' => $answer['answer'],
                ]
            );
        }

        $userSolvedTrainingOption = UserSolvedTrainingOption::where([
            'user_id' => auth()->id(),
            'training_option_id' => $trainingOption->id,
        ])->first();

        $userSolvedTrainingOption->finished_at = now();
        $userSolvedTrainingOption->is_solved = true;
        $userSolvedTrainingOption->save();

        return response()->json(['message' => 'Answers saved successfully']);
    }
}

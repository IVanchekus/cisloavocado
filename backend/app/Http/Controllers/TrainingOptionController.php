<?php

namespace App\Http\Controllers;

use App\Models\TrainingOption;
use App\Models\UserSolvedExercise;
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

        if (!$trainingOption->with_solution) {
            $exercises->each(function ($exercise) {
                $exercise->solution = null;
                $exercise->answer = null;
            });
        }

        return response()->json($exercises);
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

        return response()->json(['message' => 'Answers saved successfully']);
    }
}

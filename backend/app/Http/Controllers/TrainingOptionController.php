<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\TrainingOption;
use App\Models\UserSolvedExercise;
use App\Models\UserSolvedTrainingOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class TrainingOptionController extends Controller
{
    public function getExercises($hash)
    {
        $trainingOption = TrainingOption::where([
            'hash' => $hash,
            'is_public' => true,
            'is_approved' => true,
        ])->first();
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

    public function createExercise(Request $request)
    {
        $validated = $request->validate([
            'data.question' => 'required|string',
            'data.solution' => 'required|string',
            'data.answer' => 'required|string',
        ]);

        $data = $validated['data'];

        $exercise = Exercise::create([
            'question' => json_encode([
                'ru' => $data['question'],
                'en' => $data['question'],
            ]),
            'answer' => $data['answer'],
            'solution' => json_encode([
                'ru' => $data['solution'],
                'en' => $data['solution'],
            ]),
            'user_id' => Auth::id(),
            'subject_id' => 1,
        ]);

        return response()->json($exercise, 201);
    }

    public function updateExercise(Request $request, $id)
    {
        $validated = $request->validate([
            'data.question' => 'required|string',
            'data.solution' => 'required|string',
            'data.answer' => 'required|string',
        ]);

        $data = $validated['data'];

        $exercise = Exercise::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $exercise->question = json_encode([
            'ru' => $data['question'],
            'en' => $data['question'],
        ]);
        $exercise->solution = json_encode([
            'ru' => $data['solution'],
            'en' => $data['solution'],
        ]);
        $exercise->answer = $data['answer'];

        $exercise->save();

        return response()->json($exercise);
    }

    public function getExercise($id)
    {
        $exercise = Exercise::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json($exercise);
    }

    public function getTrainingOption($id)
    {
        $trainingOption = TrainingOption::with('exercises')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json($trainingOption);
    }

    public function createTrainingOption(Request $request)
    {
        $validated = $request->validate([
            'data.name' => 'required|string',
            'data.description' => 'nullable|string',
            'data.exercise_ids' => 'array',
            'data.exercise_ids.*' => 'integer|exists:exercises,id',
        ]);

        $data = $validated['data'];

        $trainingOption = TrainingOption::create([
            'hash' => Uuid::uuid4()->toString(),
            'name' => json_encode([
                'ru' => $data['name'],
                'en' => $data['name'],
            ]),
            'description' => isset($data['description']) && $data['description'] !== null
                ? json_encode([
                    'ru' => $data['description'],
                    'en' => $data['description'],
                ])
                : null,
            'with_solution' => false,
            'deadline' => null,
            'user_id' => Auth::id(),
            'subject_id' => 1,
            'is_public' => true,
            'is_approved' => true,
        ]);

        if (!empty($data['exercise_ids'])) {
            $trainingOption->exercises()->sync($data['exercise_ids']);
        }

        return response()->json($trainingOption, 201);
    }

    public function updateTrainingOption(Request $request, $id)
    {
        $validated = $request->validate([
            'data.name' => 'required|string',
            'data.description' => 'nullable|string',
            'data.exercise_ids' => 'array',
            'data.exercise_ids.*' => 'integer|exists:exercises,id',
        ]);

        $data = $validated['data'];

        $trainingOption = TrainingOption::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $trainingOption->name = json_encode([
            'ru' => $data['name'],
            'en' => $data['name'],
        ]);
        $trainingOption->description = isset($data['description']) && $data['description'] !== null
            ? json_encode([
                'ru' => $data['description'],
                'en' => $data['description'],
            ])
            : null;

        $trainingOption->save();

        if (isset($data['exercise_ids'])) {
            $trainingOption->exercises()->sync($data['exercise_ids']);
        }

        return response()->json($trainingOption);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Subject;
use App\Models\TrainingOption;
use App\Models\User;
use Illuminate\Database\Seeder;

class TrainingOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TrainingOptionFactory выбирает user_id/subject_id из существующих записей,
        // поэтому на пустой БД обеспечиваем минимум данных.
        if (User::query()->count() < 5) {
            User::factory()->count(5 - User::query()->count())->create();
        }

        if (Subject::query()->count() === 0) {
            $this->call([SubjectSeeder::class]);
        }

        // Обеспечиваем 100 заданий (ExerciseSeeder досоздаёт до 100).
        $this->call([ExerciseSeeder::class]);

        $exerciseIds = Exercise::query()->pluck('id')->toArray();
        $exerciseIds = collect($exerciseIds)->shuffle()->values()->all();

        $needOptions = 20 - TrainingOption::query()->count();
        if ($needOptions > 0) {
            TrainingOption::factory()->count($needOptions)->create();
        }

        $options = TrainingOption::query()->orderBy('id')->take(20)->get();
        if ($options->isEmpty()) {
            return;
        }

        // Ровное распределение: 100 заданий по 20 вариантам => по 5 на вариант.
        // Если заданий больше 100 — берём первые 100 после shuffle.
        $exerciseIds = array_slice($exerciseIds, 0, 100);
        $chunks = array_chunk($exerciseIds, (int) ceil(count($exerciseIds) / max(1, $options->count())));

        foreach ($options as $idx => $trainingOption) {
            $ids = $chunks[$idx] ?? [];
            $trainingOption->exercises(true)->sync($ids);
        }
    }
}

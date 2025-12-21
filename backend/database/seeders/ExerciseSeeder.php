<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\User;
use App\Models\CategoryExercise;
use App\Models\LevelExercise;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ExerciseFactory выбирает user_id из существующих пользователей.
        // Чтобы сидер был стабильным на пустой БД — создаём несколько пользователей при необходимости.
        if (User::query()->count() < 5) {
            User::factory()->count(5 - User::query()->count())->create();
        }

        // ExerciseFactory также использует справочники/предметы — обеспечиваем минимум данных.
        if (CategoryExercise::query()->count() === 0) {
            $this->call([CategoryExerciseSeeder::class]);
        }
        if (LevelExercise::query()->count() === 0) {
            $this->call([LevelExerciseSeeder::class]);
        }
        if (Subject::query()->count() === 0) {
            $this->call([SubjectSeeder::class]);
        }

        $need = 100 - Exercise::query()->count();
        if ($need > 0) {
            Exercise::factory()->count($need)->create();
        }
    }
}

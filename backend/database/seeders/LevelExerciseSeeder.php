<?php

namespace Database\Seeders;

use App\Models\LevelExercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LevelExercise::create([
            'name' => json_encode([
                'en' => 'Easy',
                'ru' => 'Легкий',
            ]),
            'description' => json_encode([
                'en' => 'This is a beginner level exercise.',
                'ru' => 'Это упражнение для начинающих.',
            ]),
        ]);

        LevelExercise::create([
            'name' => json_encode([
                'en' => 'Medium',
                'ru' => 'Средний',
            ]),
            'description' => json_encode([
                'en' => 'This is a intermediate level exercise.',
                'ru' => 'Это упражнение для средних.',
            ]),
        ]);

        LevelExercise::create([
            'name' => json_encode([
                'en' => 'Hard',
                'ru' => 'Сложный',
            ]),
            'description' => json_encode([
                'en' => 'This is a advanced level exercise.',
                'ru' => 'Это упражнение для продвинутых.',
            ]),
        ]);
    }
}

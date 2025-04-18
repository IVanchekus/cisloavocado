<?php

namespace Database\Seeders;

use App\Models\CategoryExercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryExercise::create([
            'name' => json_encode([
                'en' => 'Information Model Analysis',
                'ru' => 'Анализ информационных моделей',
            ]),
            'description' => json_encode([
                'en' => 'This category focuses on analyzing and understanding information models.',
                'ru' => 'Эта категория сосредоточена на анализе и понимании информационных моделей.',
            ]),
        ]);
    }
}

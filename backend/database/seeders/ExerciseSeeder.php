<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\TrainingOption;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exercise::factory()->count(30)->create();
    }
}

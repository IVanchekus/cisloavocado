<?php

namespace Database\Seeders;

use App\Models\TrainingOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainingOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TrainingOption::factory()->count(5)->create();
    }
}

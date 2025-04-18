<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::create([
            'name' => json_encode([
                'en' => 'Informatics',
                'ru' => 'Информатика',
            ]),
            'description' => json_encode([
                'en' => 'This subject covers the basics of computer science and programming.',
                'ru' => 'Этот предмет охватывает основы информатики и программирования.',
            ]),
        ]);
    }
}

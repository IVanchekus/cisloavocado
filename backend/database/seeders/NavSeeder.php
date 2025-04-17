<?php

namespace Database\Seeders;

use App\Models\Nav;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Nav::create([
            'label' => json_encode([
                'en' => 'Home',
                'ru' => 'Главная',
            ]),
            'name' => 'home',
            'component' => 'Home',
            'is_active' => true,
        ]);

        Nav::create([
            'label' => json_encode([
                'en' => 'Informatics',
                'ru' => 'Информатика',
            ]),
            'name' => 'informatics',
            'component' => 'Informatics',
            'is_active' => true,
        ]);
    }
}

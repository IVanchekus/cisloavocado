<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingOption>
 */
class TrainingOptionFactory extends Factory
{
    protected $model = \App\Models\TrainingOption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::all()->pluck('id')->toArray();
        $subjectIds = Subject::all()->pluck('id')->toArray();

        return [
            'hash' => Uuid::uuid4()->toString(),
            'name' => json_encode([
                'en' => fake()->word(),
                'ru' => fake()->word(),
            ]),
            'description' => json_encode([
                'en' => fake()->sentence(),
                'ru' => fake()->sentence(),
            ]),
            'with_solution' => fake()->boolean(),
            'deadline' => fake()->dateTimeBetween('now', '+1 year'),
            'user_id' => $this->faker->randomElement($userIds),
            'is_public' => fake()->boolean(),
            'is_approved' => fake()->boolean(),
            'subject_id' => $this->faker->randomElement($subjectIds),
        ];
    }
}

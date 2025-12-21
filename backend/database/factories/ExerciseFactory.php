<?php

namespace Database\Factories;

use App\Models\CategoryExercise;
use App\Models\Exercise;
use App\Models\LevelExercise;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::all()->pluck('id')->toArray();
        $categoryExerciseIds = CategoryExercise::all()->pluck('id')->toArray();
        $levelExerciseIds = LevelExercise::all()->pluck('id')->toArray();
        $subjectIds = Subject::all()->pluck('id')->toArray();

        return [
            'question' => json_encode([
                'en' => $this->faker->text(2000),
                'ru' => $this->faker->text(2000),
            ]),
            'answer' => $this->faker->word(),
            'solution' => json_encode([
                'ru' => $this->faker->text(2000),
                'en' => $this->faker->text(2000)
            ]),
            'img_url' => $this->faker->imageUrl(),
            'video_url' => $this->faker->url(),
            'user_id' => $this->faker->randomElement($userIds),
            'category_exercise_id' => $this->faker->optional()->randomElement($categoryExerciseIds),
            'level_exercise_id' => $this->faker->optional()->randomElement($levelExerciseIds),
            'subject_id' => $this->faker->randomElement($subjectIds),
            'is_ai' => $this->faker->boolean,
            'is_public' => $this->faker->boolean,
            'is_approved' => $this->faker->boolean,
        ];
    }
}

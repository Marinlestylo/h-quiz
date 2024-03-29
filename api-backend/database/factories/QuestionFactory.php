<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

use App\Models\Question;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'validation' => [
                'expected' => "42"
            ],
            'difficulty' => Arr::random(['easy', 'medium', 'hard', 'insane']),
            'user_id' => Arr::random([1,2]),
            'is_public' => Arr::random([true, false]),
            'points' => $this->faker->randomFloat(1, 0, 10),
            'explanation' => $this->faker->paragraph()
        ];
    }
}

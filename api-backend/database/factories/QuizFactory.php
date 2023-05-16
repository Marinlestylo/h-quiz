<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

use App\Models\Quiz;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teachers_id = User::where('affiliation', 'member;staff')->pluck('id')->toArray();
        return [
            'name' => $this->faker->sentence(4),
            'user_id' => Arr::random($teachers_id),
        ];
    }
}

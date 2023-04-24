<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = Arr::random([0 /* male */, 1 /* female */]);
        $firstname = $this->faker->firstName(['male', 'female'][$gender]);
        $lastname = $this->faker->lastName;
        $email = strtolower($firstname) . '.' . strtolower($lastname) . '@heig-vd.com';
        return [
            'name' => "$firstname $lastname",
            'email' => $this->faker->unique()->safeEmail,
            'unique_id' => $this->faker->unique()->randomNumber(),
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => 'shibboleth',
            'gender' => $gender,
            'affiliation' => 'member;student',
            'remember_token' => Str::random(10),
            'api_token' => Str::random(60),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

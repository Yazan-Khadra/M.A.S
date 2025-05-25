<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Arabic_name' => fake('ar_SA')->unique()->word,
            'English_name' => fake('en_US')->unique()->word,
            'photo' => fake()->imageUrl(),
        ];
    }
}

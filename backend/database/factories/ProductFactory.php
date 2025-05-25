<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Arabic_name' => fake()->unique()->randomElement([
                'كاسة',
                'كنزة',
                'لعبة',
                'مخدة',
                'قلم'
            ]),
            'English_name' => fake('en_US')->unique()->word,
            'photo' => fake()->imageUrl(),
            'Arabic_description' => fake()->randomElement([
                ' كاسة زجاج',
                'كنزة صوف',
                'لعبة أطفال',
                'مخدة ريش',
                'قلم رصاص'
            ]),
            'English_description' => fake()->text,
            'category_id' => Category::factory(),

        ];
    }
}

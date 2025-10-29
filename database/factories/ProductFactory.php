<?php

namespace Database\Factories;

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
            'name' => fake()->words(3, true),
            'stock' => fake()->numberBetween(0, 100),
            'price' => fake()->numberBetween(10000, 1000000),
            'desc' => fake()->paragraph(),
            'image' => null, 
        ];
    }
}

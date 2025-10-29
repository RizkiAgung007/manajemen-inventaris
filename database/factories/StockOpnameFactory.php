<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockOpname>
 */
class StockOpnameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'   => User::factory(),
            // 'notes'     => fake()->sentence(),
            'status'    => 'pending',
        ];
    }

    public function approved(): Factory
    {
        return $this->state(fn (array $attributes) => ['status' => 'approved']);
    }

    public function rejected(): Factory
    {
        return $this->state(fn (array $attributes) => ['status' => 'rejected']);
    }
}

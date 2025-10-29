<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockOpname;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockOpnameDetail>
 */
class StockOpnameDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $systemStock = $product->stock;
        $physicalStock = max(0, $systemStock + fake()->numberBetween(-5, 5));

        return [
            'stock_opname_id'   => StockOpname::factory(),
            'product_id'        => $product->id,
            'system_stock'      => $systemStock,
            'physical_stock'    => $physicalStock,
        ];
    }
}

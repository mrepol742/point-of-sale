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
            'name' => $this->faker->word(),
            'code' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'barcode' => $this->faker->unique()->regexify('[0-9]{12}'),
            'unit_measurement' => $this->faker->randomElement(['pcs', 'kg', 'liters']),
            'is_active' => $this->faker->boolean(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'category_ulid' => Category::inRandomOrder()->value('ulid'),
            'age_restriction' => $this->faker->numberBetween(0, 18),
            'description' => $this->faker->text(),
            'taxes' => $this->faker->randomFloat(2, 0, 20),
            'cost_price' => $this->faker->randomFloat(2, 1, 100),
            'markup' => $this->faker->randomFloat(2, 1, 100),
            'sale_price' => $this->faker->randomFloat(2, 1, 200),
            'color' => $this->faker->safeColorName(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}

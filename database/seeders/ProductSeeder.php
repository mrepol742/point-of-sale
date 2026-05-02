<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batchSize = 1000;
        $total = 10000;
        $products = [];

        for ($i = 0; $i < $total; $i++) {
            $products[] = [
                'user_ulid' => User::inRandomOrder()->value('ulid'),
                'ulid' => (string) Str::ulid(),
                'category_ulid' => Category::inRandomOrder()->value('ulid'),
                'code' => fake()->unique()->regexify('[A-Z0-9]{10}'),
                'barcode' => fake()->ean13(),
                'unit_measurement' => fake()->word(),
                'name' => fake()->word(),
                'quantity' => rand(1, 100),
                'age_restriction' => rand(0, 18),
                'description' => fake()->text(50),
                'cost_price' => rand(1, 100),
                'markup' => rand(1, 100),
                'sale_price' => rand(1, 100),
            ];

            if (count($products) === $batchSize) {
                Product::insert($products);
                $products = [];
            }
        }

        // Insert any remaining products
        if (!empty($products)) {
            Product::insert($products);
        }
    }
}

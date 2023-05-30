<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'sku' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'name' => $this->faker->word,
            'price' => $this->faker->numberBetween(1000, 1000000),
            'stock' => $this->faker->numberBetween(1, 100),
            'category_id' => Category::factory(),
        ];
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateProduct()
    {
        $apiKey = config('app.api_key');

        $category = Category::factory()->create();

        $response = $this->withHeaders(['Authorization' => $apiKey])->postJson('/api/products', [
            'sku' => 'TEST123',
            'name' => 'Test Product',
            'price' => 1000000,
            'stock' => 100,
            'categoryId' => $category->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'sku' => 'TEST123',
                    'name' => 'Test Product',
                    'price' => 1000000,
                    'stock' => 100,
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('products', ['sku' => 'TEST123']);
    }
}

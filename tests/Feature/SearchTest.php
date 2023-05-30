<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchProductsBySkuAndName()
    {
        $apiKey = config('app.api_key');

        $category = Category::factory()->create();

        $product1 = Product::factory()->create([
            'sku' => 'TEST001',
            'name' => 'TestProduct1',
            'category_id' => $category->id,
        ]);

        $product2 = Product::factory()->create([
            'sku' => 'TEST002',
            'name' => 'TestProduct2',
            'category_id' => $category->id,
        ]);

        $response = $this->withHeaders(['Authorization' => $apiKey])->getJson('/api/search?sku[]=TEST001&name[]=Product2');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $product1->id,
                        'sku' => 'TEST001',
                        'name' => 'TestProduct1',
                    ],
                    [
                        'id' => $product2->id,
                        'sku' => 'TEST002',
                        'name' => 'TestProduct2',
                    ],
                ],
            ]);
    }

    public function testSearchProductsWithSku()
    {
        $apiKey = config('app.api_key');
        $category = Category::factory()->create();

        $product1 = Product::factory()->create(['sku' => '1', 'category_id' => $category->id]);
        $product2 = Product::factory()->create(['sku' => '2', 'category_id' => $category->id]);
        $product3 = Product::factory()->create(['sku' => '3', 'category_id' => $category->id]);
        $product4 = Product::factory()->create(['sku' => '4', 'category_id' => $category->id]);

        $response = $this->withHeaders(['Authorization' => $apiKey])
            ->getJson('/api/search?sku[]=1&sku[]=2&sku[]=3');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    ['id' => $product1->id, 'sku' => '1'],
                    ['id' => $product2->id, 'sku' => '2'],
                    ['id' => $product3->id, 'sku' => '3'],
                ],
            ]);
    }

    public function testSearchProductsWithName()
    {
        $apiKey = config('app.api_key');
        $category = Category::factory()->create();

        $product1 = Product::factory()->create(['name' => 'ProductA', 'category_id' => $category->id]);
        $product2 = Product::factory()->create(['name' => 'ProductB', 'category_id' => $category->id]);
        $product3 = Product::factory()->create(['name' => 'ProductC', 'category_id' => $category->id]);
        $product4 = Product::factory()->create(['name' => 'ProductD', 'category_id' => $category->id]);

        $response = $this->withHeaders(['Authorization' => $apiKey])
            ->getJson('/api/search?name[]=ProductA&name[]=ProductB&name[]=ProductC');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    ['id' => $product1->id, 'name' => 'ProductA'],
                    ['id' => $product2->id, 'name' => 'ProductB'],
                    ['id' => $product3->id, 'name' => 'ProductC'],
                ],
            ]);
    }

    public function testSearchProductsWithPriceRange()
    {
        $apiKey = config('app.api_key');
        $category = Category::factory()->create();

        $product1 = Product::factory()->create(['price' => 50, 'category_id' => $category->id]);
        $product2 = Product::factory()->create(['price' => 200, 'category_id' => $category->id]);
        $product3 = Product::factory()->create(['price' => 500, 'category_id' => $category->id]);
        $product4 = Product::factory()->create(['price' => 1000, 'category_id' => $category->id]);

        $response = $this->withHeaders(['Authorization' => $apiKey])
            ->getJson('/api/search?price.start=100&price.end=1000');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    ['id' => $product2->id, 'price' => 200],
                    ['id' => $product3->id, 'price' => 500],
                    ['id' => $product4->id, 'price' => 1000],
                ],
            ]);
    }

    public function testSearchProductsWithCategory()
    {
        $apiKey = config('app.api_key');
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $category3 = Category::factory()->create();
        $category4 = Category::factory()->create();

        $product1 = Product::factory()->create(['category_id' => $category1->id]);
        $product2 = Product::factory()->create(['category_id' => $category2->id]);
        $product3 = Product::factory()->create(['category_id' => $category3->id]);
        $product4 = Product::factory()->create(['category_id' => $category4->id]);

        $response = $this->withHeaders(['Authorization' => $apiKey])
            ->getJson('/api/search?category.id[]=' . $category1->id . '&category.id[]=' . $category2->id . '&category.id[]=' . $category3->id);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    ['id' => $product1->id, 'category' => ['id' => $category1->id]],
                    ['id' => $product2->id, 'category' => ['id' => $category2->id]],
                    ['id' => $product3->id, 'category' => ['id' => $category3->id]],
                ],
            ]);
    }
}

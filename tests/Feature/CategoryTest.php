<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\CategoryController;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCategory()
    {
        $apiKey = config('app.api_key');

        $response = $this->withHeaders(['Authorization' => $apiKey])->postJson('/api/categories', ['name' => 'Test Category']);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Test Category',
                ],
            ]);

        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }
}

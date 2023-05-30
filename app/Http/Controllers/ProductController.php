<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:products',
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoryId' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $category = Category::find($request->input('categoryId'));

        $product = Product::create([
            'sku' => $request->input('sku'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'category_id' => $category->id,
        ]);

        return response()->json([
            'data' => [
                'id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'createdAt' => $product->created_at->timestamp,
            ],
        ]);
    }
}

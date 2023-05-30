<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->has('sku')) {
            $query->whereIn('sku', $request->input('sku'));
        }

        if ($request->has('name')) {
            $names = $request->input('name');
            $query->orWhere(function ($query) use ($names) {
                foreach ($names as $name) {
                    $query->orWhere('name', 'LIKE', '%' . $name . '%');
                }
            });
        }
        if ($request->has('price_start') && $request->has('price_end')) {

            $query->orWhere(function ($query) use ($request){
                $query->Where('price', '>=', $request->input('price_start'));
                $query->Where('price', '<=', $request->input('price_end'));
            });
        }


        if ($request->has('stock_start') && $request->has('stock_end')) {
            dd($request->input('stock_start'), $request->input('stock_end'));
            $query->orWhere(function ($query) use ($request){
                $query->orWhere('stock', '>=', $request->input('stock_start'));
                $query->orWhere('stock', '<=', $request->input('stock_end'));
            });
        }

        if ($request->has('category_id')) {
            $query->orWhereIn('category_id', $request->input('category_id'));
        }

        if ($request->has('category_name')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->orWhereIn('name',  $request->input('category_name'));
            });
        }

        $pageSize = $request->input('pageSize', 10);
        $currentPage = $request->input('page', 1);
        $total = $query->count();
        $query->forPage($currentPage, $pageSize);

        $products = $query->with('category')->get();

        $data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ],
                'createdAt' => $product->created_at->timestamp * 1000,
            ];
        });

        $response = [
            'data' => $data,
            'paging' => [
                'size' => $pageSize,
                'total' => $total,
                'current' => $currentPage,
            ],
        ];

        return response()->json($response);
    }
}

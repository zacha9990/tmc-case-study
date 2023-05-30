<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $category = Category::create([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'createdAt' => $category->created_at->timestamp,
            ],
        ]);
    }
}

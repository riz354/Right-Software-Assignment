<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $per_page = $request->input('per_page', 10);
            $categories = Category::paginate($per_page);
            $data =  CategoryResource::collection($categories->items());
            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $data,
                    'pagination' => [
                        'total' => $categories->total(),
                        'per_page' => $categories->perPage(),
                        'current_page' => $categories->currentPage(),
                        'last_page' => $categories->lastPage(),
                        'next_page_url' => $categories->nextPageUrl(),
                        'prev_page_url' => $categories->previousPageUrl(),
                    ]
                ],

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

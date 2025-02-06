<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\StoreComment;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomePageController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->get();

        $data = [
            'categories' => $categories
        ];
        return view('front-end.home', $data);
    }

    public function products(Request $request, $id)
    {
        $products = Product::where('category_id', $id)->get();
        $data = [
            'products' => $products
        ];
        return view('front-end.products-listing', $data);
    }

    public function productDetail(Request $request, $category_id, $product_id)
    {
        $product = Product::find($product_id);
        $data = [
            'product' => $product
        ];
        return view('front-end.product-detail', $data);
    }


    public function addComment(StoreComment $request, $id)
    {
        try {
           $comment =  ProductComment::create([
                'product_id' => $id,
                'user_id' => Auth::user()->id,
                'comment' => $request->comment,
            ]);
            $product = Product::find($id);
            $view = view('front-end.product-reviews',compact('product'))->render();
            $comment->load('user');
            return response()->json([
                'success' => true,
                'message' => "Comment added successfully",
                'view'=>$view
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}

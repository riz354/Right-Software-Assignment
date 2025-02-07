<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request, $id)
    {
        $products = Product::where('category_id', $id)->paginate(10);
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
}

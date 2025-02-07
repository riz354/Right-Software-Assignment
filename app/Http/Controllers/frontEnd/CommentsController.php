<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\StoreComment;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store(StoreComment $request, $id)
    {
        try {
            $comment =  ProductComment::create([
                'product_id' => $id,
                'user_id' => Auth::user()->id,
                'comment' => $request->comment,
            ]);
            $product = Product::find($id);
            $view = view('front-end.product-reviews', compact('product'))->render();
            $comment->load('user');
            return response()->json([
                'success' => true,
                'message' => "Comment added successfully",
                'view' => $view
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function updateComment(StoreComment $request, $id)
    {
        try {
            $comment = ProductComment::findOrFail($id);
            $comment->comment = $request->comment;
            $comment->save();
            $product = $comment->product;
            $view = view('front-end.product-reviews', compact('product'))->render();
            return response()->json([
                'success' => true,
                'message' => "Comment updated successfully",
                'view' => $view
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    
}

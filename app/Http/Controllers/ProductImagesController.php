<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImages\StoreRequest;
use App\Jobs\ProcessProductImages;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImagesController extends Controller
{
    public function index(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            $data = [
                'product' => $product,
            ];
            return view('admin.product.images', $data);
        } catch (\Throwable $th) {
            return redirect()->route('admin.product.index')->withError('Something Went Wrong');
        }
    }

    public function store(StoreRequest $request, $productId)
    {
        try {
            if ($request->hasFile('images')) {
                $imagesPath = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('temp-products', 'public');
                    $imagesPath[] =  $path;
                }
                dispatch(new ProcessProductImages($productId,  $imagesPath));
            }
            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $productImage = ProductImage::find($id);
            if (Storage::disk('public')->exists($productImage->image_path)) {
                Storage::disk('public')->delete($productImage->image_path);
            }
            $productImage->delete();
            return response()->json([
                'success' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

   
}

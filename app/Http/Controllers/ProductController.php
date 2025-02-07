<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Jobs\ProcessProductImages;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::get();
            return DataTables::of($data)
                ->editColumn('id', function ($product) {
                    return $product->id ?? '-';
                })
                ->editColumn('name', function ($product) {
                    return $product->name ?? '-';
                })
                ->editColumn('category', function ($product) {
                    return $product->category->name ?? '-';
                })
                ->editColumn('image', function ($product) {
                    $images = $product->images;
                    $images_preview = '<div class="row">';
                    foreach ($images as $image) {
                        $path = asset('storage/' . $image->image_path);
                        $images_preview .= '
                            <div class="col-4 text-center">
                                <img src="' . $path . '" class="img-thumbnail m-0 p-0" width="50" height="50">
                            </div>
                        ';
                    }
                    $images_preview .= '</div>';
                    return $images_preview;
                })


                ->editColumn('price', function ($product) {
                    return $product->price ?? '-';
                })->editColumn('description', function ($product) {
                    return $product->description ?? '-';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($product) {
                    $editBtn = '<a href="javascript:void(0)" class="btn btn-sm btn-primary edit-btn" data-id="' . $product->id . '" data-name="' . $product->name . '"><i class="fa fa-pen-to-square"></i></a>';
                    $deleteBtn = '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-btn" data-id="' . $product->id . '"><i class="fa fa-trash"></i></a>';
                    $imagesBtn = '<a href="' . route('product.images', ['id' => $product->id]) . '" class="btn btn-sm btn-primary "><i class="fa fa-image"></i></a>';
                    return $editBtn . ' ' . $deleteBtn . ' ' . $imagesBtn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        $categories = Category::get();
        $data = [
            'categories' => $categories
        ];

        return view('product.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $product = Product::create([
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                    'price' => $request->price,
                    'description' => $request->description,
                ]);

                if ($request->hasFile('images')) {
                    $imagesPath = [];
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('temp-products', 'public');
                        $imagesPath[] =  $path;
                    }
                    dispatch(new ProcessProductImages($product->id,  $imagesPath));
                }
            });

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $product = Product::find($id);
            $images = $product->images;
            $imagesPath = [];
            if (isset($images)) {
                foreach ($images as $image) {
                    $imagesPath[] = asset('storage/' . $image->image_path);
                }
            }
            return response()->json([
                'success' => true,
                'product' => $product,
                'imagesPath' => $imagesPath
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::transaction(function () use ($request, $id) {
                $product = Product::updateOrCreate(['id' => $id], [
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                    'price' => $request->price,
                    'description' => $request->description,
                ]);
                if ($request->hasFile('images')) {
                    $product->images()->delete();
                    $imagesPath = [];
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('temp-products', 'public');
                        $imagesPath[] =  $path;
                    }
                    dispatch(new ProcessProductImages($product->id,  $imagesPath));
                }
            });

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
            DB::transaction(function () use ($id) {
                $product = Product::find($id);
                if ($product) {
                    $images = $product->images;
                    foreach ($images as $image) {
                        if (Storage::disk('public')->exists($image->image_path)) {
                            Storage::disk('public')->delete($image->image_path);
                        }
                    }
                    $product->delete();
                    $product->images()->delete();
                    return response()->json(['success' => true]);
                }
            });

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

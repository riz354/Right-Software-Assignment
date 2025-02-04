<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
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
                ->editColumn('price', function ($product) {
                    return $product->price ?? '-';
                })->editColumn('description', function ($product) {
                    return $product->description ?? '-';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($product) {
                    $editBtn = '<a href="javascript:void(0)" class="btn btn-sm btn-primary edit-btn" data-id="' . $product->id . '" data-name="' . $product->name . '"><i class="fa fa-pen-to-square"></i></a>';
                    $deleteBtn = '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-btn" data-id="' . $product->id . '"><i class="fa fa-trash"></i></a>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $categories = Category::get();
        $data = [
            'categories' => $categories
        ];

        return view('product.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'required|string',
        ]);

        try {
            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($image);
                    $img = $img->resize(800, 800);
                    $img = $img->toJpeg(80);
                    $imagePath = 'products/' . $imageName;
                    $img->save(storage_path('app/public/' . $imagePath));


                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }
           
            return response()->json([
                'success' => true,
                'product' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $product = Product::find($id);
            return response()->json(['success' => true, 'product' => $product]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }


    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if ($product) {
                $images = $product->images;
                foreach ($images as $image) {
                    $filePath = storage_path('app/public/' . $image->image_path);  
                    if (File::exists($filePath)) {
                        Storage::delete('public/' . $image->image_path); 
                    }
                }
                $product->delete();
                $product->images()->delete();
                return response()->json(['success' => true]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}


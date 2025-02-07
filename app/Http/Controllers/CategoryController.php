<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::select('id','name')->orderBy('id','desc')->get();
            return DataTables::of($data)
                ->editColumn('id', function ($category) {
                    return $category->id ?? '-';
                })
                ->editColumn('name', function ($category) {
                    return $category->name ?? '-';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="javascript:void(0)" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '" data-name="' . $row->name . '"><i class="fa fa-pen-to-square"></i></a>';
                    $deleteBtn = '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '"><i class="fa fa-trash"></i></a>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('category.index');
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
    public function store(StoreRequest $request)
    {
        try {
            $category = Category::create(['name' => $request->name]);
            return response()->json([
                'success' => true,
                'category' => $category,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
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
    public function edit(Request $request, $id)
    {
        try {
            $category = Category::find($id);
            return response()->json([
                'success' => true,
                'category' => $category,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $category = Category::updateOrCreate(['id' => $id], ['name' => $request->name]);
            return response()->json([
                'success' => true,
                'category' => $category,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}

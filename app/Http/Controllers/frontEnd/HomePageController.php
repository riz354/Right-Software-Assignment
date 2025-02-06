<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;

class HomePageController extends Controller
{
    public function index()
    {
        $categories = Category::whereHas('products')
            ->paginate(20);
        $data = [
            'categories' => $categories
        ];
        return view('front-end.home', $data);
    }

   

   


   
}

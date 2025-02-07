<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\frontEnd\CommentsController;
use App\Http\Controllers\frontEnd\HomePageController;
use App\Http\Controllers\frontEnd\ProductsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomePageController::class,'index']);
Route::get('/category/{id}/products',[ProductsController::class,'index'])->name('category.products');
Route::get('/category/{category_id}/product/{product_id}/detail',[ProductsController::class,'productDetail'])->name('category.product.detail');

Route::get('/dashboard', function () {
    return redirect()->route('category.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix'=>'category','as'=>'category.'],function(){
        Route::get('/',[CategoryController::class,'index'])->name('index');
        Route::post('/store',[CategoryController::class,'store'])->name('store');
        Route::get('/edit/{id}',[CategoryController::class,'edit'])->name('edit');
        Route::put('/update/{id}',[CategoryController::class,'update'])->name('update');
        Route::delete('/destroy/{id}',[CategoryController::class,'destroy'])->name('destroy');
    });

    Route::group(['prefix'=>'product','as'=>'product.'],function(){
        Route::get('/',[ProductController::class,'index'])->name('index');
        Route::post('/store',[ProductController::class,'store'])->name('store');
        Route::get('/edit/{id}',[ProductController::class,'edit'])->name('edit');
        Route::post('/update/{id}',[ProductController::class,'update'])->name('update');
        Route::delete('/destroy/{id}',[ProductController::class,'destroy'])->name('destroy');
        Route::get('/{id}/images',[ProductImagesController::class,'index'])->name('images');
        Route::post('/{id}/images/store',[ProductImagesController::class,'store'])->name('images.store');
        Route::delete('/image/{id}/destroy',[ProductImagesController::class,'destroy'])->name('image.destroy');
        Route::post('/product/{id}/comment',[CommentsController::class,'store'])->name('comment');
        Route::post('/product/{id}/comment/update',[CommentsController::class,'updateComment'])->name('comment.update');

    });
});

require __DIR__.'/auth.php';

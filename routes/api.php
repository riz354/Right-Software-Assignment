<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register',[UserController::class, 'register']);
Route::post('/login',[UserController::class, 'login']);


Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::post('/categories',[CategoryController::class, 'index']);
    Route::post('/products',[ProductController::class, 'index']);
});
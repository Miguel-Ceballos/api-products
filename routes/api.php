<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('products', ProductController::class);
Route::get('categories/{id}/products', [CategoryController::class, 'categoryProducts']);
Route::get('brands/{id}/products', [BrandController::class, 'brandProducts']);

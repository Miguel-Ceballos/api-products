<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('purchases', PurchaseController::class);

Route::get('categories/{id}/products', [ CategoryController::class, 'categoryProducts' ]);
Route::get('brands/{id}/products', [ BrandController::class, 'brandProducts' ]);

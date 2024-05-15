<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $brands = Brand::all();
            return ApiResponse::success('Brands list successfully returned.', 200, $brands);
        } catch (Exception $exception) {
            return ApiResponse::error('Error to return brands: ' . $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => [ 'required', 'string', 'max:50', Rule::unique('brands') ],
                'description' => [ 'max:255' ]
            ]);
            $category = Category::create($request->all());
            return ApiResponse::success('Brand successfully created.', 200, $category);
        } catch (ValidationException $exception) {
            return ApiResponse::error('Validation error', 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        $bran
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //
    }
}

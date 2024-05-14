<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return ApiResponse::success('Categories returned successfully.', 200, $categories);
        } catch (Exception $exception) {
            return ApiResponse::error('Error when returned categories: ' . $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => [ 'required', 'string', 'max:50', Rule::unique('categories') ],
                'description' => [ 'max:255' ]
            ]);
            $category = Category::create($request->all());
            return ApiResponse::success('Category successfully created', 200, $category);
        } catch (ValidationException $exception) {
            return ApiResponse::error('Validation error', 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}

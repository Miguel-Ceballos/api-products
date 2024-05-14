<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return ApiResponse::success('Category returned successfully.', 200, $category);
        } catch (ModelNotFoundException) {
            return ApiResponse::error('Category not found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $request->validate([
                'name' => [ 'required', 'string', 'max:50', Rule::unique('categories')->ignore($category) ],
                'description' => [ 'max:255' ]
            ]);
            $category->update($request->all());
            return ApiResponse::success('Category successfully updated.', 200, $category);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Category not found.', 404);
        } catch (Exception $exception) {
            return ApiResponse::error('Error: ' . $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return ApiResponse::success('Category successfully deleted.', 200);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Category not found.', 404);
        }
    }
}

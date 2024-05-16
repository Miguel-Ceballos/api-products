<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::all();
//            $products = Product::with('brand', 'category')->get();
            return ApiResponse::success('Products successfully returned.', 200, $products);
        } catch (Exception $exception) {
            return ApiResponse::error('Error getting information.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => [ 'required', 'string', 'max:50', Rule::unique('products') ],
                'description' => [ 'required', 'max:255' ],
                'price' => [ 'required', 'numeric', 'between:0,999999.99' ],
                'stock' => [ 'required', 'integer' ],
                'category_id' => [ 'required', 'exists:categories,id' ],
                'brand_id' => [ 'required', 'exists:brands,id' ]
            ]);
            $product = Product::create($request->all());
            return ApiResponse::success('Product created successfully', 201, $product);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->toArray();
            return ApiResponse::error('Validation error: ' . $errors, 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = Product::with('brand', 'category')->findOrFail($id);
            return ApiResponse::success('Product returned successfully.', 200, $product);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Product not found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $request->validate([
                'name' => [ 'required', 'string', 'max:50', Rule::unique('products')->ignore($product) ],
                'description' => [ 'required', 'max:255' ],
                'price' => [ 'required', 'numeric', 'between:0,999999.99' ],
                'stock' => [ 'required', 'integer' ],
                'category_id' => [ 'required', 'exists:categories,id' ],
                'brand_id' => [ 'required', 'exists:brands,id' ]
            ]);
            $product->update($request->all());
            return ApiResponse::success('Product updated successfully.', 200, $product);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->toArray();
            return ApiResponse::error('Validation error: ' . $errors, 422);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Product not found.', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return ApiResponse::success('Product deleted successfully', 200);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::error('Product not found.', 404);
        }

    }
}

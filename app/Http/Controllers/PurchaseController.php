<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Models\Purchase;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $products = $request->input('products');

            // validar los productos
            if ( empty($products) ) {
                return ApiResponse::error('No se proporcionaron productos.', 400);
            }

            //validar la lista de productos
            $validator = Validator::make($request->all(), [
                'products' => [ 'required', 'array' ],
                'products.*.product_id' => [ 'required', 'integer', 'exists:products,id' ],
                'products.*.quantity' => [ 'required', 'integer', 'min:1' ]
            ]);

            if ( $validator->fails() ) {
                return ApiResponse::error('Datos inválidos en la lista de productos.', 400, $validator->errors());
            }

            // validar productos duplicados
            $productsId = array_column($products, 'product_id');

            if ( count($productsId) !== count(array_unique($productsId)) ) {
                return ApiResponse::error('No se permiten productos duplicados para la compra.', 400);
            }

            $total = 0;
            $subtotal = 0;
            $purchaseItems = [];

            //Iteración de los productos a pagar
            foreach ( $products as $product ) {
                $productItem = Product::find($product['product_id']);
                if ( ! $productItem ) {
                    return ApiResponse::error('Product not found.', 404);
                }

                // validar la cantidad disponible de los productos
                if ( $productItem->stock < $product['quantity'] ) {
                    return ApiResponse::error('El producto no tiene suficiente cantidad disponible.', 404);
                }

                # Actualización de la cantidad disponible de cada producto
                $productItem->stock -= $product['quantity'];
                $productItem->save();

                # Cálculo de los importes
                $subtotal = $productItem->price * $product['quantity'];
                $total += $subtotal;

                # Items de la compra
                $purchaseItems[] = [
                    'product_id' => $productItem->id,
                    'price' => $productItem->price,
                    'quantity' => $product['quantity'],
                    'subtotal' => $subtotal
                ];
            }

            # Registro en la tabla compras
            $purchase = Purchase::create([
                'subtotal' => $total,
                'total' => $total
            ]);

            # Asociar los productos a la compra con sus cantidades y sus subtotales
            $purchase->products()->attach($purchaseItems);

            return ApiResponse::success('Compra realizada exitosamente.', 201, $purchase);

        } catch (QueryException $exception) {
            return ApiResponse::error('Error en la consulta de base de datos.', 500, $exception->getMessage());
        } catch (Exception $exception) {
            return ApiResponse::error('Error inesperado.', 500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}

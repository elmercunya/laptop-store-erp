<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductApiController extends Controller
{
    public function index(Request $request) {

        $query = Product::with('category');

        $search = $request->input('search');

        if($request->filled('search')) {
            $query->where(function($q1) use($search) {
                $q1->where('name', 'LIKE', '%'.$search.'%')->orWhereHas('category', function($q2) use($search) {
                    $q2->where('name', 'LIKE', '%'.$search.'%');
                });
            });
        }

        if($request->filled('category_id')) {
            $query->where('category_id',$request->category_id);
        }

        $per_page = min((int) $request->input('per_page', 10), 50);
        
        $products = $query->paginate($per_page);
        
        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);

    }

    public function show($id) {

        $product = Product::with('category')->find($id);


        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product), 
        ]);
    }


}

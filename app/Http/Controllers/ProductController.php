<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $busqueda = $request->input('busqueda');

        $query = Product::query();
        
        if($busqueda) {
            $query->where('name', 'LIKE' , '%'.$busqueda.'%');
        }

        $products = $query->with('category')->paginate(10);


        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!auth()->user()?->isAdmin()) {
            abort(403, 'No tienes permisos para crear productos');
        }

        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('message', 'Producto creado exitosamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $product = Product::find($id);

        $categories = Category::all(); 

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if($request->hasFile('image')) {
            if($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image']  = $request->file('image')->store('products', 'public');
        }

        $product->update($data);


        return redirect()->route('products.index')->with('message', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        $product->delete();

        return redirect()->route('products.index')->with('message', 'Producto eliminado exitosamente');
    }
}

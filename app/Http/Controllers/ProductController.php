<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'desc'  => 'nullable|string|',
        ]);

        Product::create([
            'name'  => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'desc'  => $request->desc,
        ]);

        return redirect()->route('products.index')->with('success', 'produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'  => 'required|string|msx:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'desc'  => 'nullable|string'
        ]);

        $product->update([
            'name'  => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'desc'  => $request->desc,
        ]);

        return redirect()->route('products.index')->with('success', 'produk berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, string $id)
    {
        $product->delete();

        return redirect()->route('product.index')->with('success', 'produk berhasil dihapus');
    }
}

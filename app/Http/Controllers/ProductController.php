<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Traits\ManagesStock;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ManagesStock;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name', 'asc')->get();

        $query = Product::with('categories', 'suppliers');

        // Mencari nama produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Mencari berdasarkan kategori
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Sort
        if ($request->filled('sort')) {
            $sortOption = $request->sort;

            if ($sortOption == 'name_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($sortOption == 'name_desc') {
                $query->orderBy('name', 'desc');
            } elseif ($sortOption == 'date_asc') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sortOption == 'date_desc') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sortOption == 'stock_asc') {
                $query->orderBy('stock', 'asc');
            } elseif ($sortOption == 'stock_desc') {
                $query->orderBy('stock', 'desc');
            }
        } else {
            $query->latest();
        }

        // $products = Product::with('category')->latest()->get()->query()->paginate(10);
        $products = $query->paginate(10);

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'stock'         => 'required|integer|min:0',
            'price'         => 'required|numeric|min:0',
            'desc'          => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'categories'    => 'required|array',
            'categories.*'  => 'exists:categories,id',
            'suppliers'     => 'required|array',
            'suppliers.*'   => 'exists:suppliers,id'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        $product = Product::create([
            'name'  => $validatedData['name'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'desc'  => $validatedData['desc'] ?? null,
            'image' => $validatedData['image'] ?? null,
        ]);

        $product->categories()->sync($validatedData['categories']);
        $product->suppliers()->sync($validatedData['suppliers']);

        if ($product->stock > 0) {
            $this->recordStockMovement($product, $product->stock, 'stok_awal', [
                'notes' => 'Stok awal saat dibuat'
            ]);
        }

        // LOGGING
        auth()->user()->activityLogs()->create([
            'activity'    => "Menambahkan produk baru: {$product->name}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('categories', 'suppliers');
        $stockMovements = $product->stockMovements()->with('user')->paginate(10);

        return view('products.show', compact('product', 'stockMovements'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $product->load('categories', 'suppliers');
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'stock'       => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'desc'        => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'categories'  => 'required|array',
            'categories.*'=> 'exists:categories,id',
            'suppliers'   => 'nullable|array',
            'suppliers.*' => 'exists:suppliers,id',
        ]);

        $oldStock = $product->stock;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        $product->update($validatedData);

        $product->categories()->sync($validatedData['categories']);
        $product->suppliers()->sync($validatedData['suppliers'] ?? []);

        $newStock = $product->fresh()->stock;

        if ($oldStock !== $newStock) {
            $quantityChange = $newStock - $oldStock;
            $this->recordStockMovement($product, $quantityChange, 'penyesuaian_manual', [
                'notes' => 'Stok diubah.'
            ], false);
        }

        // LOGGING
        auth()->user()->activityLogs()->create([
            'activity'    => "Memperbarui produk: {$product->name}",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent')
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $productName = $product->name;
        $product->delete();

        auth()->user()->activityLogs()->create([
            'activity'    => "Menghapus produk: {$productName}",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->header('User-Agent'),
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}

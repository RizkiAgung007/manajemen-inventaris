<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $categories = Category::withCount('products')->with('suppliers')->latest()->paginate(15);

    // dd($categories->first());
    return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('categories.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'suppliers' => 'nullable|array',
            'suppliers.*' => 'exists:suppliers,id',
        ]);

        $category = Category::create(['name' => $validatedData['name']]);

        if ($request->has('suppliers')) {
            $category->suppliers()->sync($validatedData['suppliers']);
        }

        auth()->user()->activityLogs()->create([
            'activity'   => "Menambahkan kategori baru: {$category->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('products.supplier', 'suppliers');
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $category->load('suppliers');
        return view('categories.edit', compact('category', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'suppliers' => 'nullable|array',
            'suppliers.*' => 'exists:suppliers,id',
        ]);

        $category->update(['name' => $validatedData['name']]);
        $category->suppliers()->sync($request->suppliers ?? []);

        auth()->user()->activityLogs()->create([
            'activity'   => "Memperbarui kategori: {$category->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {

        $deleteCategory = $category->name;
        $category->delete();

        // LOGGING
        auth()->user()->activityLogs()->create([
            'activity'      => "Menghapus kategori: {$deleteCategory}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header(('User-Agent')),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}

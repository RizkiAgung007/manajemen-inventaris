<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name'
        ], [
            'name.required' => 'Nama kategori tidak boleh kosong.',
            'name.unique'   => 'Nama ketegori ini sudah ada.'
        ]);

        $newCategory = Category::create([
            'name'  => $request->name,
        ]);

        //LOGGING
        auth()->user()->activityLogs()->create([
            'activity'      => "Menambahkan kategori baru: {$newCategory->name}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header(('User-Agent')),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name'
        ], [
            'name.required' => 'Nama kategori tidak boleh kosong.',
            'name.unique'   => 'Nama kategori ini sudah ada.'
        ]);

        $category->update([
            'name' => $request->name
        ]);

        // LOGGING
        auth()->user()->activityLogs()->create([
            'activity'      => "Memperbarui kategori: {$category->name}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header(('User-Agent')),
        ]);

        return redirect()->route('categories.index')->with('success', 'Ketgori berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {

        $deleteCategory = $category->name;
        $category->delete();

        // LOGGING
        auth()->user()->activityLogs()->create([
            'activity'      => "Menghapus kategori: {$deleteCategory}",
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->header(('User-Agent')),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}

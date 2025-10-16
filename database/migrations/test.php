// app/Http/Controllers/ProductController.php

public function update(Request $request, Product $product)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'desc' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        'categories' => 'required|array',
        'categories.*' => 'exists:categories,id',
        'suppliers' => 'nullable|array',
        'suppliers.*' => 'exists:suppliers,id',
    ]);

    $oldStock = $product->stock;

    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $validatedData['image'] = $request->file('image')->store('products', 'public');
    }

    // Stok di sini sudah diubah menjadi nilai baru
    $product->update($validatedData);

    // Sinkronkan relasi setelah update
    $product->categories()->sync($validatedData['categories']);
    $product->suppliers()->sync($validatedData['suppliers'] ?? []);

    $newStock = $product->fresh()->stock; // Gunakan fresh() untuk mendapatkan nilai terbaru dari DB

    if ($oldStock != $newStock) {
        $quantityChange = $newStock - $oldStock;

        // [PERBAIKAN] Panggil trait dengan parameter kelima 'false'
        // Ini memberitahu trait untuk HANYA MENCATAT, tidak meng-update stok lagi.
        $this->recordStockMovement($product, $quantityChange, 'penyesuaian_manual', [
            'notes' => 'Stok diubah via form edit produk'
        ], false);
    }

    // LOGGING
    auth()->user()->activityLogs()->create([
        'activity'   => "Memperbarui produk: {$product->name}",
        'ip_address' => $request->ip(),
        'user_agent' => $request->header('User-Agent')
    ]);

    return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
}

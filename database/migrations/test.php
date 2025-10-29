public function approve(StockOpname $stockOpname, Request $request)
{
    if ($stockOpname->status !== 'pending') {
        return redirect()->route('reviews.show', $stockOpname)->with('error', 'Laporan ini sudah diproses sebelumnya.');
    }

    // --- TAMBAHKAN BARIS INI ---
    // Eager load relasi detail dan produknya SEBELUM transaksi
    $stockOpname->load('details.product');

    DB::transaction(function () use ($stockOpname) {

        // Sekarang $stockOpname->details sudah berisi produknya
        foreach ($stockOpname->details as $detail) {

            // --- UBAH BARIS INI ---
            // Ambil produk dari relasi yang sudah di-load, JANGAN query lagi
            $product = $detail->product;

            if ($product) {
                $quantityChange = $detail->physical_stock - $detail->system_stock;

                if ($quantityChange != 0) {
                    $product->increment('stock', $quantityChange);

                    $this->recordStockMovement($product, $quantityChange, 'stok_opname', [
                        'notes' => "Dari Laporan Stok Opname #" . $stockOpname->id
                    ], false);
                }
            }
        }

        $stockOpname->status = 'approved';
        $stockOpname->save();
    });

    auth()->user()->activityLogs()->create([
        //... sisa kode Anda
    ]);

    return redirect()->route('reviews.index')->with('success', 'Laporan berhasil disetujui.');
}

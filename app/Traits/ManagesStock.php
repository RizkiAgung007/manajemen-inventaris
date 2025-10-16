<?php

namespace App\Traits;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait ManagesStock
{
    /**
     * Mencatat pergerakan stok dan memperbarui jumlah stok produk.
     *
     * @param Product $product Produk yang stoknya berubah.
     * @param int $quantity Jumlah perubahan (bisa positif atau negatif).
     * @param string $type Tipe transaksi.
     * @param array $options Opsi tambahan seperti notes.
     */
    protected function recordStockMovement(Product $product, int $quantity, string $type, array $options = [], bool $updateStock = true)
    {
        DB::transaction(function () use ($product, $quantity, $type, $options, $updateStock) {
            $product->stockMovements()->create([
                'user_id'   => Auth::id(),
                'type'      => $type,
                'quantity'  => $quantity,
                'notes'     => $options['notes'] ?? null,
            ]);

            if ($updateStock) {
                $product->increment('stock', $quantity);
            }
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Traits\ManagesStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{

    use ManagesStock;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockOuts = StockMovement::where('quantity', '<', 0)->with('product', 'user')->latest()->paginate(20);

        return view('stock-out.index', compact('stockOuts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('stock-out.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'type'          => 'required|in:penjualan,barang_rusak,pemakaian_internal',
            'notes'         => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $product->stock)->withInput();
        }
        try {
            DB::transaction(function () use ($product, $validated, $request) {
                $this->recordStockMovement(
                    $product,
                    -1 * $validated['quantity'],
                    $validated['type'],
                    ['notes' => $validated['notes']]
                );

                // LOGGING
                auth()->user()->activityLogs()->create([
                    'activity'      => "Menambahkan barang keluar: ({$validated['type']}): {$product->name} (Qty: {$validated['quantity']})",
                    'ip_address'    => $request->ip(),
                    'user_agent'    => $request->header('User-Agent')
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat barang keluar: ' . $e->getMessage());
        }

        return redirect()->route('stock-out.index', $product)->with('success', 'Barang keluar berhasil dicatat dan stok telah diupdate.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, StockMovement $stockMovement)
    {
        if ($stockMovement->status !== 'cancelled') {
            return back()->with('error', 'Hanya transaksi yang sudah dibatalkan yang bisa dihapus.');
        }

        try {
            $stockMovementId = $stockMovement->id;
            $productName = $stockMovement->product->name;

            $stockMovement->delete();

            // LOGGING
            auth()->user()->activityLogs()->create([
                'activity'   => "Menghapus catatan (dibatalkan): #{$stockMovementId} ({$productName})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus catatan: ' . $e->getMessage());
        }

        return redirect()->route('stock-out.index')->with('success', 'Catatan transaksi yang dibatalkan berhasil dihapus.');
    }

    public function cancel(Request $request, StockMovement $stockMovement)
    {
        if ($stockMovement->status !== 'completed') {
            return back()->with('error', 'Hanya transaksi yang sudah selesai yang bisa dibatalkan.');
        }

        try {
            DB::transaction(function () use ($stockMovement, $request) {

                $this->recordStockMovement(
                    $stockMovement->product,
                    -1 * $stockMovement->quantity,
                    'pembatalan',
                    ['notes' => "Pembatalan transaksi barang keluar #" . $stockMovement->id]
                );

                $stockMovement->status = 'cancelled';
                $stockMovement->save();

                auth()->user()->activityLogs()->create([
                    'activity'   => "Membatalkan transaksi barang keluar: #{$stockMovement->id} ({$stockMovement->product->name})",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }

        return redirect()->route('stock-out.index')->with('success', 'Transaksi berhasil dibatalkan dan stok telah dikembalikan.');
    }
}

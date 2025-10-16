<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Traits\ManagesStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    use ManagesStock;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $purchaseOrders = PurchaseOrder::with('supplier', 'user')->withSum('products as total_value', DB::raw('purchase_order_product.quantity * purchase_order_product.price'))->latest()->paginate(15);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchase-orders.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'notes'       => 'nullable|string',
            'products'    => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
            'products.*.price'      => 'required|numeric|min:0',
        ]);

        $purchaseOrder = null;

        try {
            DB::transaction(function () use ($validated) {
                $purchaseOrder = PurchaseOrder::create([
                    'supplier_id' => $validated['supplier_id'],
                    'user_id'     => Auth::id(),
                    'notes'       => $validated['notes'],
                    'status'      => 'pending',
                ]);

                foreach ($validated['products'] as $productData) {
                    $purchaseOrder->products()->attach($productData['product_id'], [
                        'quantity' => $productData['quantity'],
                        'price'    => $productData['price'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }

        auth()->user()->activityLogs()->create([
            'activity'      => "Menambahkan pembelian barang: {$purchaseOrder->id}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
        ]);

        return redirect()->route('purchase-orders.index')->with('success', 'Pesanan pembelian baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'user', 'products');

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status != 'pending') {
            return redirect()->route('purchase-orders.show', $purchaseOrder)->with('error', 'Pesanan yang sudah selesai tidak dapat diedit.');
        }

        $suppliers = Supplier::orderBy('name')->get();
        $purchaseOrder->load('products');

        return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id'           => 'required|exists:suppliers,id',
            'notes'                 => 'nullable|string',
            'products'              => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
            'products.*.price'      => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validated, $purchaseOrder) {
                $purchaseOrder->update([
                    'supplier_id' => $validated['supplier_id'],
                    'notes'       => $validated['notes'],
                ]);

                $syncData = [];
                foreach ($validated['products'] as $productData) {
                    $syncData[$productData['product_id']] = [
                        'quantity' => $productData['quantity'],
                        'price'    => $productData['price'],
                    ];
                }

                $purchaseOrder->products()->sync($syncData);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage())->withInput();
        }

        auth()->user()->activityLogs()->create([
            'activity'      => "Mengedit pembelian barang: {$purchaseOrder->id}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
        ]);

        return redirect()->route('purchase-orders.index', $purchaseOrder)->with('success', 'Pesanan pembelian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        try {
            $purchaseOrder->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
    }

    auth()->user()activityLogs()->create([
        'activity'      => "Menghapus pembelian barang: {$purchaseOrder->id}",
        'ip_address'    => $request->ip(),
        'user_agent'    => $request->header('User-Agent'),
    ]);

    return redirect()->route('purchase-orders.index')->with('success', 'Pesanan pembelian berhasil dihapus.');
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Pesanan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            foreach ($purchaseOrder->products as $product) {
                $quantityReceived = $product->pivot->quantity;

                $this->recordStockMovement($product, $quantityReceived, 'barang_masuk', [
                    'notes' => "Dari Pesanan Pembelian #" . $purchaseOrder->id
                ]);
            }

            $purchaseOrder->status = 'completed';
            $purchaseOrder->save();
        });

        auth()->user()->activityLogs()->create([
            'activity'      => "Menghapus pembelian barang: {$purchaseOrder->id}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
        ]);

        return redirect()->route('purchase-orders.show', $purchaseOrder)->with('success', 'Pesanan berhasil diterima dan stok berhasil diupdate.');
    }
}

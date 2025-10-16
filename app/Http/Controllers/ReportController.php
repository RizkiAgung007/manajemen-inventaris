<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Product;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name', 'asc')->get();
        return view('reports.index', compact('products'));
    }

    public function exportExcel()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');

        return Excel ::download(new ProductsExport, "laporan_produk_${timestamp}.xlsx");
    }

    public function exportPdf(Request $request)
    {
        $physicalStocks = $request->input('physical_stock', []);
        $products = Product::with('category')->find(array_keys($physicalStocks));

        DB::transaction(function () use ($products, $physicalStocks) {
            // Buat record utama laporan
            $stockOpname = StockOpname::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
            ]);

            // Simpan detail setiap produk
            foreach ($products as $product) {
                $stockOpname->details()->create([
                    'product_id' => $product->id,
                    'system_stock' => $product->stock,
                    'physical_stock' => $physicalStocks[$product->id] ?? null,
                ]);
            }
        });

        $reportData = [];
        foreach ($products as $product) {
            $physicalStock = $physicalStocks[$product->id] ?? null;
            $variance = ($physicalStock !== null) ? (int)$physicalStock - $product->stock : null;

            $reportData[] = [
                'name' => $product->name,
                'category' => $product->category->name ?? 'N/A',
                'system_stock' => $product->stock,
                'physical_stock' => $physicalStock,
                'variance' => $variance,
            ];
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $pdf = PDF::loadView('reports.pdf', ['reportData' => $reportData]);
        return $pdf->download("laporan_stok_opname_{$timestamp}.pdf");
    }

    public function store(Request $request)
    {
        $request->validate([
            'physical_stock' => 'required|array',
            'physical_stock.*' => 'nullable|integer|min:0',
        ]);

        $physicalStocks = $request->input('physical_stock', []);
        $products = Product::find(array_keys($physicalStocks));

        $stockOpname = null;

        DB::transaction(function () use ($request, $products, $physicalStocks, &$stockOpname) {
            $stockOpname = StockOpname::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
            ]);

            foreach ($products as $product) {
                if (isset($physicalStocks[$product->id])) {
                    $stockOpname->details()->create([
                        'product_id' => $product->id,
                        'system_stock' => $product->stock,
                        'physical_stock' => $physicalStocks[$product->id],
                    ]);
                }
            }
        });

        if ($stockOpname) {
            auth()->user()->activityLogs()->create([
                'activity'   => "Mengirim laporan stok opname baru: #{$stockOpname->id}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }

        return redirect()->route('reports.index')->with('success', 'Laporan stok opname berhasil dikirim untuk direview oleh Admin.');
    }

    public function myReports(StockOpname $stockOpname)
    {
        $myReports = $stockOpname::where('user_id', auth()->id())->latest()->paginate(15);

        return view('reports.my-reports', compact('myReports'));
    }
}

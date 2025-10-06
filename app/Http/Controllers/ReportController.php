<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $products = Product::with('category')->get();
        $physicalStocks = $request->input('physical_stock', []);
        $timestamp = now()->format('Y-m-d_H-i-s');

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

        $pdf = PDF::loadView('reports.pdf', ['reportData' => $reportData]);
        return $pdf->download("laporan_stok_opname_{$timestamp}.pdf");
    }
}

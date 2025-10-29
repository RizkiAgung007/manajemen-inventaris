<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOpname;
use App\Traits\ManagesStock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameReviewController extends Controller
{
    use ManagesStock;

    public function index()
    {
        $pendingReports = StockOpname::where('status', 'pending')->with('user')->latest()->paginate(15);

        return view('reviews.index', [
            'pendingReports'        => $pendingReports,
            'pendingReportsCount'   => $pendingReports->total()
        ]);
    }


    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load('details.product.categories', 'user');

        return view('reviews.show', compact('stockOpname'));
    }

    public function approve(StockOpname $stockOpname, Request $request)
    {
        if ($stockOpname->status !== 'pending') {
            return redirect()->route('reviews.show', $stockOpname)->with('error', 'Laporan ini sudah diproses sebelumnya.');
        }

        $stockOpname->load('details.product');

        DB::transaction(function () use ($stockOpname) {
            foreach ($stockOpname->details as $detail) {

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
            'activity'      => "Menyetujui laporan stok opname: #{$stockOpname->name}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
        ]);

        return redirect()->route('reviews.index')->with('success', 'Laporan berhasil disetujui.');
    }

    public function reject(StockOpname $stockOpname, Request $request)
    {
        if ($stockOpname->status !== 'pending') {
            return redirect()->route('reviews.show', $stockOpname)->with('error', 'Laporan ini sudah diproses sebelumnya.');
        }

        $stockOpname->status = 'rejected';
        $stockOpname->save();

        auth()->user()->activityLogs()->create([
            'activity'      => "Menolak laporan stok opname: #{$stockOpname->id}",
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent')
        ]);

        return redirect()->route('reviews.index')->with('success', 'Laporan berhasil ditolak.');

    }

        public function downloadPdf(StockOpname $stockOpname)
    {
        $stockOpname->load('details.product.category');

        $reportData = [];
        foreach ($stockOpname->details as $detail) {
            $variance = $detail->physical_stock - $detail->system_stock;
            $reportData[] = [
                'name' => $detail->product->name,
                'category' => $detail->product->category->name ?? 'N/A',
                'system_stock' => $detail->system_stock,
                'physical_stock' => $detail->physical_stock,
                'variance' => $variance,
            ];
        }

        $timestamp = $stockOpname->created_at->format('Y-m-d');
        $pdf = PDF::loadView('reports.pdf', ['reportData' => $reportData]);
        return $pdf->download("laporan_stok_opname_{$stockOpname->id}_{$timestamp}.pdf");
    }

}

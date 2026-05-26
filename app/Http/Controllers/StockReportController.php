<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\PDF;

class StockReportController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with('product', 'product.category', 'product.brand')
            ->orderBy('stock', 'asc')
            ->get();

        $totalStock = $variants->sum('stock');
        $totalProduk = $variants->groupBy('product_id')->count();
        $totalVariant = $variants->count();

        $lowStock = $variants->filter(function ($v) {
            return $v->stock > 0 && $v->stock < 10;
        });

        $outOfStock = $variants->filter(function ($v) {
            return $v->stock == 0;
        });

        return view('laporan.stok.index', compact(
            'variants', 'totalStock', 'totalProduk', 'totalVariant', 'lowStock', 'outOfStock'
        ));
    }

    public function export()
    {
        $variants = ProductVariant::with('product', 'product.category', 'product.brand')
            ->orderBy('stock', 'asc')
            ->get();

        $totalStock = $variants->sum('stock');
        
        // LOAD PDF VIEW
        $pdf = PDF::loadView('laporan.stok.pdf', [
            'variants' => $variants,
            'totalStock' => $totalStock,
            'tanggal' => now()->format('d F Y')
        ]);
        
        // DOWNLOAD
        return $pdf->download('laporan-stok-' . now()->format('Y-m-d') . '.pdf');
    }
}
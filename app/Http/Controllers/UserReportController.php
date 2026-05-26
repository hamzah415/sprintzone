<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Product;

class StockReportController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with('product', 'product.category', 'product.brand')
            ->orderBy('stock', 'asc')
            ->get();

        $totalStock = $variants->sum('stock');
        $totalVariant = $variants->count();
        $totalProduk = $variants->groupBy('product_id')->count();  // TAMBAHIN
        $totalKategori = $variants->groupBy('product_id')->pluck('product.category_id')->filter()->count();  // TAMBAHIN

        // Low stock
        $lowStock = $variants->filter(function ($v) {
            return $v->stock > 0 && $v->stock < 10;
        });

        // Out of stock
        $outOfStock = $variants->filter(function ($v) {
            return $v->stock == 0;
        });

        return view('laporan.stok.index', compact(
            'variants',
            'totalStock',
            'totalVariant',
            'totalProduk',
            'totalKategori',
            'lowStock',
            'outOfStock'
        ));
    }

    public function export()
    {
        $variants = ProductVariant::with('product')->orderBy('stock', 'asc')->get();

        $filename = 'laporan-stok-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($variants) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Produk', 'Kategori', 'Brand', 'Warna', 'Size', 'SKU', 'Harga', 'Stok', 'Status']);

            foreach ($variants as $index => $v) {
                if ($v->stock == 0) $status = 'OUT OF STOCK';
                elseif ($v->stock < 5) $status = 'LOW STOCK';
                else $status = 'READY';

                fputcsv($file, [
                    $index + 1,
                    $v->product->name ?? '-',
                    $v->product->category->name ?? '-',
                    $v->product->brand->name ?? '-',
                    $v->color ?? '-',
                    $v->size ?? '-',
                    $v->sku ?? '-',
                    number_format($v->price, 0, ',', '.'),
                    $v->stock,
                    $status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

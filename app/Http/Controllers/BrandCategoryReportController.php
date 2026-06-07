<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use PDF;

class BrandCategoryReportController extends Controller
{
    /**
     * Laporan per Brand dengan breakdown Kategori
     */
    public function index()
    {
        // Ambil semua brand dengan produknya
        $brands = Brand::with(['products', 'products.category'])
            ->get()
            ->map(function($brand) {
                // Total per brand
                $brand->total_orders = OrderItem::whereHas('variant.product', function($q) use ($brand) {
                    $q->where('brand_id', $brand->id);
                })->count();
                
                $brand->total_qty = OrderItem::whereHas('variant.product', function($q) use ($brand) {
                    $q->where('brand_id', $brand->id);
                })->sum('qty');
                
                $brand->total_pendapatan = OrderItem::whereHas('variant.product', function($q) use ($brand) {
                    $q->where('brand_id', $brand->id);
                })->sum('subtotal');
                
                // Breakdown per kategori dalam brand ini
                $categories = Category::all()->map(function($cat) use ($brand) {
                    $cat->total_orders = OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                        $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                    })->count();
                    
                    $cat->total_qty = OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                        $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                    })->sum('qty');
                    
                    $cat->total_pendapatan = OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                        $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                    })->sum('subtotal');
                    
                    return $cat;
                })->filter(function($cat) { return $cat->total_orders > 0; });
                
                $brand->categories = $categories;
                
                return $brand;
            })
            ->filter(function($brand) { return $brand->total_orders > 0; })
            ->sortByDesc('total_pendapatan');
        
        // Stats total
        $totalBrand = $brands->count();
        $totalOrders = $brands->sum('total_orders');
        $totalQty = $brands->sum('total_qty');
        $totalPendapatan = $brands->sum('total_pendapatan');
        
        return view('laporan.brand-kategori.index', compact(
            'brands', 'totalBrand', 'totalOrders', 'totalQty', 'totalPendapatan'
        ));
    }

    /**
     * Export CSV
     */
    public function export()
    {
        // (kode yang sudah ada)
        $brands = Brand::with(['products', 'products.category'])->get()
            ->map(function($brand) {
                $brand->total_pendapatan = OrderItem::whereHas('variant.product', function($q) use ($brand) {
                    $q->where('brand_id', $brand->id);
                })->sum('subtotal');
                return $brand;
            });
        
        $filename = 'laporan-brand-kategori-' . now()->format('Y-m-d') . '.csv';
        
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];

        $callback = function() use ($brands) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Brand', 'Kategori', 'Total Order', 'Qty', 'Pendapatan']);
            
            $no = 1;
            foreach ($brands as $brand) {
                fputcsv($file, [
                    $no++,
                    $brand->name,
                    '(TOTAL)',
                    $brand->total_orders,
                    $brand->total_qty,
                    number_format($brand->total_pendapatan, 0, ',', '.')
                ]);
                
                foreach (Category::all() as $cat) {
                    $orders = OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                        $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                    })->count();
                    
                    if ($orders > 0) {
                        fputcsv($file, [
                            '',
                            '  > ' . $brand->name,
                            $cat->name,
                            $orders,
                            OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                                $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                            })->sum('qty'),
                            number_format(OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                                $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                            })->sum('subtotal'), 0, ',', '.')
                        ]);
                    }
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export PDF (Tambah ini)
     */
    public function exportPDF()
    {
        // Ambil data sama seperti index
        $brands = Brand::with(['products', 'products.category'])
            ->get()
            ->map(function($brand) {
                $brand->total_orders = OrderItem::whereHas('variant.product', function($q) use ($brand) {
                    $q->where('brand_id', $brand->id);
                })->count();
                
                $brand->total_qty = OrderItem::whereHas('variant.product', function($q) use ($brand) {
                    $q->where('brand_id', $brand->id);
                })->sum('qty');
                
                $brand->total_pendapatan = OrderItem::whereHas('variant.product', function($q) use ($brand) {
                    $q->where('brand_id', $brand->id);
                })->sum('subtotal');
                
                $categories = Category::all()->map(function($cat) use ($brand) {
                    $cat->total_orders = OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                        $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                    })->count();
                    
                    $cat->total_qty = OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                        $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                    })->sum('qty');
                    
                    $cat->total_pendapatan = OrderItem::whereHas('variant.product', function($q) use ($brand, $cat) {
                        $q->where('brand_id', $brand->id)->where('category_id', $cat->id);
                    })->sum('subtotal');
                    
                    return $cat;
                })->filter(function($cat) { return $cat->total_orders > 0; });
                
                $brand->categories = $categories;
                
                return $brand;
            })
            ->filter(function($brand) { return $brand->total_orders > 0; })
            ->sortByDesc('total_pendapatan');

        $totalBrand = $brands->count();
        $totalOrders = $brands->sum('total_orders');
        $totalQty = $brands->sum('total_qty');
        $totalPendapatan = $brands->sum('total_pendapatan');

        $tanggal = now()->format('d/m/Y');
        $tanggalFile = now()->format('d-m-Y');

        $pdf = PDF::loadview('laporan.brand-kategori.pdf', [
            'brands' => $brands,
            'totalBrand' => $totalBrand,
            'totalOrders' => $totalOrders,
            'totalQty' => $totalQty,
            'totalPendapatan' => $totalPendapatan,
            'tanggal' => $tanggal,
        ]);

        return $pdf->download("laporan-brand-kategori-{$tanggalFile}.pdf");
    }
}
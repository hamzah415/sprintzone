<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CategoryReportController extends Controller
{
    public function index()
    {
        // Penjualan per kategori
        $categories = Category::with(['products.variants'])
            ->get()
            ->map(function($cat) {
                // Hitung total penjualan per kategori
                $cat->total_orders = OrderItem::whereHas('variant.product', function($q) use ($cat) {
                    $q->where('category_id', $cat->id);
                })->count();
                
                $cat->total_qty = OrderItem::whereHas('variant.product', function($q) use ($cat) {
                    $q->where('category_id', $cat->id);
                })->sum('qty');
                
                $cat->total_pendapatan = OrderItem::whereHas('variant.product', function($q) use ($cat) {
                    $q->where('category_id', $cat->id);
                })->sum('subtotal');
                
                return $cat;
            })
            ->sortByDesc('total_pendapatan');
        
        return view('laporan.kategori.index', compact('categories'));
    }

    public function export()
    {
        $categories = Category::with('products.variants')->get()
            ->map(function($cat) {
                $cat->total_pendapatan = OrderItem::whereHas('variant.product', function($q) use ($cat) {
                    $q->where('category_id', $cat->id);
                })->sum('subtotal');
                return $cat;
            });
        
        $filename = 'laporan-kategori-' . now()->format('Y-m-d') . '.csv';
        
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];

        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Kategori', 'Total Order', 'Total Qty', 'Pendapatan']);
            
            foreach ($categories as $index => $cat) {
                fputcsv($file, [
                    $index + 1,
                    $cat->name,
                    $cat->total_orders ?? 0,
                    $cat->total_qty ?? 0,
                    number_format($cat->total_pendapatan ?? 0, 0, ',', '.')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        $filter = $request->filter ?? 'today';
        
        $query = Order::with(['user', 'items.variant.product', 'items.variant']);
        
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
                break;
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        $totalOrder = $orders->count();
        $totalQuantity = $orders->flatMap->items->sum('qty');
        $totalPendapatan = $orders->sum('total_price');
        
        return view('laporan.penjualan.index', compact(
            'orders', 'filter', 'totalOrder', 'totalQuantity', 'totalPendapatan'
        ));
    }

    /**
     * Report Custom Date
     */
    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        
        $orders = Order::with(['user', 'items.variant.product', 'items.variant'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filter = 'custom';
        
        return view('laporan.penjualan.index', compact(
            'orders', 'filter', 'startDate', 'endDate'
        ))->with('totalOrder', $orders->count())
          ->with('totalQuantity', $orders->flatMap->items->sum('qty'))
          ->with('totalPendapatan', $orders->sum('total_price'));
    }

    /**
     * Export Excel/CSV
     */
    public function export(Request $request)
    {
        $filter = $request->filter ?? 'today';
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        // Build query
        $query = Order::with(['items.variant.product', 'items.variant']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } else {
            switch ($filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        // Filename
        $filename = 'laporan-penjualan-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No', 
                'Tanggal', 
                'Order ID', 
                'Customer', 
                ' Produk',
                'Variant',
                'Qty',
                'Harga',
                'Subtotal',
                'Status',
                'Total Order'
            ]);
            
            $no = 1;
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    fputcsv($file, [
                        $no++,
                        $order->created_at->format('d/m/Y H:i'),
                        '#' . $order->id,
                        $order->customer_name ?? 'Guest',
                        $item->variant->product->name ?? '-' ,
                        ($item->variant->color ?? '') . ' / ' . ($item->variant->size ?? ''),
                        $item->qty,
                        number_format($item->price, 0, ',', '.'),
                        number_format($item->subtotal, 0, ',', '.'),
                        $order->status,
                        number_format($order->total_price, 0, ',', '.')
                    ]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
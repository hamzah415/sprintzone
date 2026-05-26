<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    /**
     * Page Index
     */
    public function index()
    {
        return view('laporan.penjualan.index');
    }

    /**
     * Report Penjualan
     */
    public function report(Request $request)
    {
        // Default: bulan ini
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        // Ambil orders dengan items dan variant
        $orders = Order::with(['user', 'items.variant.product', 'items.variant'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();

        // Stats
        $totalOrder = $orders->count();
        $totalQuantity = $orders->flatMap->items->sum('qty');  // ← qty
        $totalPendapatan = $orders->sum('total_price');

        return view('laporan.penjualan.report', compact(
            'orders',
            'totalPendapatan',
            'totalOrder',
            'totalQuantity',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export CSV
     */
    public function export(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $orders = Order::with(['user', 'items.variant'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        $filename = 'laporan-penjualan-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['No', 'Tanggal', 'Order ID', 'Customer', 'Items', 'Total']);

            foreach ($orders as $index => $order) {
                // Hitung total items
                $totalItems = $order->items->sum('qty');

                fputcsv($file, [
                    $index + 1,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->id,
                    $order->customer_name ?? $order->user->name ?? 'Guest',
                    $totalItems,
                    number_format($order->total_price, 0, ',', '.'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

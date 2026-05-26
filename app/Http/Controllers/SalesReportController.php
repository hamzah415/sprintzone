<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->filter ?? 'all';

        $query = Order::with(['user', 'items.variant.product', 'items.variant']);

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

        $orders = $query->orderBy('created_at', 'desc')->get();

        $totalOrder = $orders->count();
        $totalQuantity = $orders->flatMap->items->sum('qty');
        $totalPendapatan = $orders->sum('total_price');

        return view('laporan.penjualan.index', compact(
            'orders',
            'filter',
            'totalOrder',
            'totalQuantity',
            'totalPendapatan'
        ));
    }

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

        $totalOrder = $orders->count();
        $totalQuantity = $orders->flatMap->items->sum('qty');
        $totalPendapatan = $orders->sum('total_price');

        return view('laporan.penjualan.index', compact(
            'orders',
            'filter',
            'totalOrder',
            'totalQuantity',
            'totalPendapatan'
        ));
    }

    public function export(Request $request)
    {
        $filter = $request->filter ?? 'all';

        $query = Order::with(['items.variant.product', 'items.variant']);

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

        $orders = $query->orderBy('created_at', 'desc')->get();

        $totalRevenue = $orders->sum('total_price');
        $totalProfit = $totalRevenue * 0.2;
        $totalQty = $orders->flatMap->items->sum('qty');

        // Label tanggal berdasarkan filter
        if ($filter == 'today') {
            $labelPeriode = now()->format('d F Y');
        } elseif ($filter == 'week') {
            $labelPeriode = 'Minggu Ini';
        } elseif ($filter == 'month') {
            $labelPeriode = now()->format('F Y');
        } else {
            $labelPeriode = 'Semua Periode';
        }

        // PDF
        $pdf = PDF::loadView('laporan.penjualan.pdf', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'totalProfit' => $totalProfit,
            'totalQty' => $totalQty,
            'filter' => $filter,
            'tanggal' => $labelPeriode
        ])
        ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . now()->format('Y-m-d') . '.pdf');
    }
}
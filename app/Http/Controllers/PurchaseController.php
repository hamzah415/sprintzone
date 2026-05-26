<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->filter ?? 'all';

        // Query dasar dengan relasi
        $query = Order::with(['user', 'items.variant.product', 'items.variant']);

        // Filter berdasarkan waktu
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
            case 'all':
            default:
                // Tidak ada filter
                break;
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('admin.purchase.index', compact('orders', 'filter'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.variant.product', 'items.variant');
        return view('admin.purchase.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,success,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status updated!');
    }
}

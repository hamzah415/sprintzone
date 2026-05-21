<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'items.variant'])
            ->latest()
            ->get();

        return view('admin.purchase.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.purchase.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status updated');
    }
}

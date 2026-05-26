<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\ProductVariant;

class AdminController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalAdmins = User::where('role', 'admin')->count();

        // Order stats
        $recentProducts = Product::with(['brand', 'variants'])
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Order stats baru
        $totalOrders = Order::count();
        $totalSuccessOrders = Order::where('status', 'success')->count();
        $totalRevenue = Order::where('status', 'success')->sum('total_price');
        $totalProfit = $totalRevenue * 0.2; // 20% estimasi

        // Low stock (stok < 5)
        $lowStockVariants = ProductVariant::where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->with('product')
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalBrands',
            'totalCategories',
            'totalAdmins',
            'recentProducts',
            'recentOrders',
            'totalRevenue',
            'totalSuccessOrders',
            // Baru
            'totalOrders',
            'totalProfit',
            'lowStockVariants'
        ));
    }
}
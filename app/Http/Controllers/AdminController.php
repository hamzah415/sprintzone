<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

        $totalBrands = Brand::count();

        $totalCategories = Category::count();

        $totalAdmins = User::where('role', 'admin')->count();

        $recentProducts = Product::with('brand')
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $totalSuccessOrders = Order::where('status', 'success')
            ->count();

        $totalRevenue = Order::where('status', 'success')
            ->sum('total_price');

        return view('dashboard', compact(
            'totalProducts',
            'totalBrands',
            'totalCategories',
            'totalAdmins',
            'recentProducts',
            'recentOrders',
            'totalRevenue',
            'totalSuccessOrders',
        ));
    }
}
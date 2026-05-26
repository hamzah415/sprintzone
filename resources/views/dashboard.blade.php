@extends('layouts.admin.app')

@section('content')
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter text-gray-900">
                    Overview <span class="text-orange-500">Dashboard</span>
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    Welcome back, <span class="text-gray-900 font-bold">{{ Auth::user()->name }}</span> 👋
                </p>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-xs text-gray-400">{{ now()->format('l, d F Y') }}</p>
                <p class="text-xl font-black text-orange-500">{{ now()->format('H:i') }}</p>
            </div>
        </div>

        {{-- STATS CARDS - CLEAN ROUNDED --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            {{-- PRODUCTS --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-3xl shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase opacity-70 font-semibold">Products</p>
                        <p class="text-3xl font-black italic">{{ number_format($totalProducts) }}</p>
                    </div>
                </div>
            </div>

            {{-- ORDERS --}}
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-6 rounded-3xl shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase opacity-70 font-semibold">Orders</p>
                        <p class="text-3xl font-black italic">{{ number_format($totalOrders) }}</p>
                    </div>
                </div>
            </div>

            {{-- REVENUE --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-3xl shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase opacity-70 font-semibold">Revenue</p>
                        <p class="text-2xl font-black italic">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- PROFIT --}}
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-3xl shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase opacity-70 font-semibold">Profit</p>
                        <p class="text-2xl font-black italic">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
                        <p class="text-[9px] text-white/60">~20% estimasi</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- SECONDARY STATS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            {{-- BRANDS --}}
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-[10px] text-gray-400 uppercase">Brands</p>
                <p class="text-2xl font-black">{{ $totalBrands }}</p>
            </div>

            {{-- CATEGORIES --}}
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-[10px] text-gray-400 uppercase">Categories</p>
                <p class="text-2xl font-black">{{ $totalCategories }}</p>
            </div>

            {{-- ADMINS --}}
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-[10px] text-gray-400 uppercase">Admins</p>
                <p class="text-2xl font-black">{{ $totalAdmins }}</p>
            </div>

            {{-- LOW STOCK --}}
            <div class="bg-white p-4 rounded-xl border border-red-100 shadow-sm">
                <p class="text-[10px] text-red-400 uppercase">Low Stock</p>
                <p class="text-2xl font-black text-red-500">{{ $lowStockVariants->count() }}</p>
            </div>

        </div>

        {{-- LOW STOCK WARNING --}}
        @if ($lowStockVariants->count() > 0)
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-8">
                <h3 class="font-black text-sm text-red-600 mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Low Stock Warning
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($lowStockVariants as $variant)
                        <span class="bg-white px-3 py-1 rounded-lg text-xs border border-red-100">
                            {{ $variant->product->name ?? '-' }}
                            <span class="text-red-500 font-bold">{{ $variant->size }}</span>
                            <span class="text-red-400">({{ $variant->stock }})</span>
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- MAIN CONTENT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- RECENT PRODUCTS --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-black italic uppercase text-sm">
                        <i class="fas fa-box text-orange-500 mr-2"></i> Recent Products
                    </h3>
                    <a href="{{ route('products.index') }}" class="text-xs text-orange-500 font-bold hover:underline">
                        View All →
                    </a>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($recentProducts as $product)
                        <div class="p-4 hover:bg-gray-50 transition flex items-center gap-4">
                            @php $firstVariant = $product->variants->first(); @endphp
                            @if ($firstVariant && $firstVariant->image)
                                <img src="{{ asset('storage/' . $firstVariant->image) }}"
                                    class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-bold text-sm">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->brand->name ?? '-' }} •
                                    {{ $product->variants->count() }} variant</p>
                            </div>
                            <div class="text-right">
                                @if ($product->status == 'active')
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-600 rounded text-[9px] font-bold uppercase">Active</span>
                                @else
                                    <span
                                        class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-[9px] font-bold uppercase">Inactive</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400">
                            No products yet
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ACTIVITY --}}
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-5 text-white shadow-lg">
                <h3 class="font-black italic uppercase text-sm mb-6">
                    <i class="fas fa-bolt text-orange-500 mr-2"></i> Activity
                </h3>

                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="flex gap-3 items-start">
                            <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-xs font-bold">{{ $order->customer_name ?? 'Guest' }}</p>
                                <p class="text-[10px] text-gray-400">{{ $order->created_at->format('d M Y • H:i') }}</p>
                                <p class="text-xs text-green-400 font-bold mt-1">
                                    +Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-400 text-center py-4">
                            No recent activity
                        </div>
                    @endforelse
                </div>

                <div class="mt-6 pt-4 border-t border-gray-700">
                    <p class="text-[10px] text-gray-400 uppercase mb-3">Quick Links</p>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('products.index') }}"
                            class="text-xs bg-gray-700/50 hover:bg-gray-700 px-3 py-2 rounded-lg transition">
                            <i class="fas fa-box mr-1"></i> Products
                        </a>
                        <a href="{{ route('laporan.penjualan') }}"
                            class="text-xs bg-gray-700/50 hover:bg-gray-700 px-3 py-2 rounded-lg transition">
                            <i class="fas fa-chart-line mr-1"></i> Reports
                        </a>
                        <a href="{{ route('purchase.history') }}"
                            class="text-xs bg-gray-700/50 hover:bg-gray-700 px-3 py-2 rounded-lg transition">
                            <i class="fas fa-shopping-cart mr-1"></i> Orders
                        </a>
                        <a href="{{ route('laporan.stok') }}"
                            class="text-xs bg-gray-700/50 hover:bg-gray-700 px-3 py-2 rounded-lg transition">
                            <i class="fas fa-boxes mr-1"></i> Stok
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@extends('layouts.admin.app')

@section('content')
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">

        <div class="mb-10">
            <h2 class="text-3xl font-black italic uppercase tracking-tighter text-gray-900">
                Overview Dashboard
            </h2>
            <p class="text-sm text-gray-500 font-medium">Welcome back, {{ Auth::user()->name }}! Here's a summary of today's
                activities.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Products</div>
                <div class="text-4xl font-black italic tracking-tighter text-gray-800">{{ number_format($totalProducts) }}
                </div>
                <div class="mt-2 text-[10px] text-green-500 font-bold">3 produk baru ditambahkan</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Active Brands</div>
                <div class="text-4xl font-black italic tracking-tighter text-gray-800">{{ $totalBrands }}</div>
                <div class="mt-2 text-[10px] text-orange-500 font-bold">3 Brand baru ditambahkan</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Categories</div>
                <div class="text-4xl font-black italic tracking-tighter text-gray-800">{{ $totalCategories }}</div>
                <div class="mt-2 text-[10px] text-gray-400 font-bold">Stabil</div>
            </div>

            <div class="bg-black p-6 rounded-2xl shadow-lg hover:shadow-xl transition">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">User Admins</div>
                <div class="text-4xl font-black italic tracking-tighter text-white">{{ $totalAdmins }}</div>
                <a href="{{ route('admin.users.index') }}" class="mt-2 text-[10px] text-orange-400 font-bold underline cursor-pointer">Manage Access</a>
            </div>

            <div class="bg-red-500 p-6 rounded-2xl shadow-lg hover:shadow-xl transition">
                <div class="text-[10px] font-bold text-red-100 uppercase tracking-widest mb-1">Low Stock Products</div>
                <div class="text-4xl font-black italic tracking-tighter text-white">{{ $lowStockProducts }}</div>
                <div class="mt-2 text-[10px] text-red-100 font-bold">Needs restock immediately</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Order</div>
                <div class="text-4xl font-black italic tracking-tighter text-gray-800">{{ $totalSuccessOrders }}</div>
                <div class="mt-2 text-[10px] text-gray-400 font-bold">Stabil</div>
            </div>

            <div class="bg-green-500 p-6 rounded-2xl shadow-lg hover:shadow-xl transition">

                <div class="text-[10px] font-bold text-green-100 uppercase tracking-widest mb-1">
                    Total Revenue
                </div>
                <div class="text-3xl md:text-4xl font-black italic tracking-tighter text-white">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[10px] text-green-100 font-bold">
                    Total transaksi sukses
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-black italic uppercase text-sm tracking-tight">Recent Products</h3>
                    <a href="/products" class="text-[10px] font-bold text-orange-600 hover:underline no-underline">
                        View All
                    </a>
                </div>
                <div class="bg-white rounded-3xl border border-red-100 shadow-sm overflow-hidden">

                    <div class="p-6 border-b border-red-50 flex items-center justify-between">

                        <h3 class="font-black italic uppercase text-sm tracking-tight text-red-500">

                            Low Stock Alert

                        </h3>

                        <span class="text-[10px] font-black uppercase bg-red-100 text-red-500 px-3 py-1 rounded-full">

                            {{ $lowStockProducts }} Products

                        </span>

                    </div>

                    <div class="divide-y divide-gray-100">

                        @forelse($lowStockList as $product)
                            <div class="p-5 flex items-center justify-between hover:bg-gray-50 transition">

                                <div>

                                    <div class="font-black text-sm text-gray-800">

                                        {{ $product->name }}

                                    </div>

                                    <div class="text-[11px] text-gray-400 uppercase font-bold mt-1">

                                        {{ $product->brand->name ?? 'No Brand' }}

                                    </div>

                                </div>

                                <div class="text-right">

                                    <div class="text-red-500 font-black text-lg">

                                        {{ $product->stock }}

                                    </div>

                                    <div class="text-[10px] uppercase text-gray-400 font-bold">

                                        Remaining

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="p-10 text-center text-gray-400 text-sm font-medium">

                                No low stock products.

                            </div>
                        @endforelse

                    </div>

                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase">
                            <tr>
                                <th class="px-6 py-4">Product Name</th>
                                <th class="px-6 py-4">Brand</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-medium text-gray-700 divide-y divide-gray-50">

                            @foreach ($recentProducts as $product)
                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4">

                                        {{ $product->name }}

                                    </td>

                                    <td class="px-6 py-4 font-bold italic">

                                        {{ $product->brand->name ?? '-' }}

                                    </td>

                                    <td class="px-6 py-4">

                                        @if ($product->stock <= 5)
                                            <span
                                                class="px-2 py-1 bg-orange-100 text-orange-600 rounded-full text-[9px] font-bold uppercase">

                                                Low Stock

                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-[9px] font-bold uppercase">

                                                Active

                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="text-gray-400 hover:text-black italic font-bold">

                                            Edit

                                        </a>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gray-900 rounded-3xl p-6 text-white shadow-xl">
                <h3 class="font-black italic uppercase text-sm tracking-tight mb-6 text-orange-400">Activity Log</h3>
                <div class="space-y-6">

                    @foreach ($recentOrders as $order)
                        <div class="flex gap-4">

                            <div class="w-1 h-10 bg-orange-500 rounded-full"></div>

                            <div>

                                <div class="text-[10px] text-gray-400 font-bold uppercase">

                                    {{ $order->created_at->format('d M Y H:i') }}

                                </div>

                                <div class="text-xs font-bold italic">

                                    {{ $order->customer_name }}
                                    placed an order worth
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>
                <a href="{{ route('purchase.history') }}"
                    class="block w-full mt-8 border border-gray-700 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 transition text-center text-white no-underline">

                    View History

                </a>
            </div>

        </div>
    </div>
@endsection

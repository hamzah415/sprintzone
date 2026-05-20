@extends('layouts.admin.app')

@section('content')
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">

        <div class="mb-10">
            <h2 class="text-3xl font-black italic uppercase tracking-tighter text-gray-900">
                Overview Dashboard
            </h2>

            <p class="text-sm text-gray-500 font-medium">
                Welcome back, {{ Auth::user()->name }}
            </p>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                    Total Products
                </div>

                <div class="text-4xl font-black italic tracking-tighter text-gray-800">
                    {{ number_format($totalProducts) }}
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                    Active Brands
                </div>

                <div class="text-4xl font-black italic tracking-tighter text-gray-800">
                    {{ $totalBrands }}
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                    Total Categories
                </div>

                <div class="text-4xl font-black italic tracking-tighter text-gray-800">
                    {{ $totalCategories }}
                </div>
            </div>

            <div class="bg-black p-6 rounded-2xl shadow-lg">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                    User Admins
                </div>

                <div class="text-4xl font-black italic tracking-tighter text-white">
                    {{ $totalAdmins }}
                </div>

                <a href="{{ route('admin.users.index') }}"
                    class="mt-2 inline-block text-[10px] text-orange-400 font-bold underline">
                    Manage Access
                </a>
            </div>

        </div>

        {{-- CONTENT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- RECENT PRODUCTS --}}
            <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

                <div class="p-6 border-b border-gray-50 flex justify-between items-center">

                    <h3 class="font-black italic uppercase text-sm tracking-tight">
                        Recent Products
                    </h3>

                    <a href="{{ route('products.index') }}"
                        class="text-[10px] font-bold text-orange-600 hover:underline no-underline">
                        View All
                    </a>

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

                            @forelse ($recentProducts as $product)
                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-6 py-4 font-bold italic">
                                        {{ $product->brand->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4">

                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-[9px] font-bold uppercase">

                                            {{ $product->status }}

                                        </span>

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="text-gray-400 hover:text-black italic font-bold">

                                            Edit

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400">

                                        No products found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- ACTIVITY --}}
            <div class="bg-gray-900 rounded-3xl p-6 text-white shadow-xl">

                <h3 class="font-black italic uppercase text-sm tracking-tight mb-6 text-orange-400">
                    Activity Log
                </h3>

                <div class="space-y-6">

                    @forelse ($recentOrders as $order)

                        <div class="flex gap-4">

                            <div class="w-1 h-10 bg-orange-500 rounded-full"></div>

                            <div>

                                <div class="text-[10px] text-gray-400 font-bold uppercase">

                                    {{ $order->created_at->format('d M Y H:i') }}

                                </div>

                                <div class="text-xs font-bold italic">

                                    {{ $order->customer_name }}

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-sm text-gray-400">

                            No recent activity.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>
@endsection
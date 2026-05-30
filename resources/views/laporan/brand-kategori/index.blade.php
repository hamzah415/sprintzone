@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-black italic uppercase tracking-tight">
                    LAPORAN <span class="text-orange-500">BRAND</span>
                </h2>
                <p class="text-sm text-gray-400 mt-1">Penjualan per brand dan kategori</p>
            </div>
            <a href="{{ route('laporan.brand-kategori.export') }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-black uppercase hover:bg-green-700 transition">
                <i class="fas fa-download mr-1"></i> EXPORT CSV
            </a>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            <div class="bg-blue-500 text-white p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase">Total Brand</p>
                        <p class="text-2xl font-black">{{ $totalBrand }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-600 text-white p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase">Total Order</p>
                        <p class="text-2xl font-black">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-500 text-white p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase">Total Qty</p>
                        <p class="text-2xl font-black">{{ $totalQty }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-500 text-white p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase">Pendapatan</p>
                        <p class="text-2xl font-black">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ACCORDION TABLE --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            @forelse($brands as $index => $brand)
                <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }" class="border-b border-gray-100 last:border-0">
                    {{-- BRAND HEADER --}}
                    <button @click="open = !open"
                        class="w-full p-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            {{-- GAMBAR DARI BRAND IMAGE --}}
                            @if ($brand->image)
                                <img src="{{ asset('storage/' . $brand->image) }}"
                                    class="w-12 h-12 object-contain rounded-lg bg-white border border-gray-200">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <span class="font-black text-gray-400 text-sm">{{ substr($brand->name, 0, 2) }}</span>
                                </div>
                            @endif
                            <div class="text-left">
                                <h3 class="font-black text-gray-900">{{ $brand->name }}</h3>
                                <p class="text-xs text-gray-400">{{ $brand->categories->count() }} kategori aktif</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden md:block">
                                <p class="text-xs text-gray-400">{{ $brand->total_orders }} order •
                                    {{ $brand->total_qty }} qty</p>
                                <p class="font-black text-green-600">Rp
                                    {{ number_format($brand->total_pendapatan, 0, ',', '.') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transform transition" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    {{-- KATEGORI BREAKDOWN --}}
                    <div x-show="open" x-cloak class="bg-gray-50">
                        @if ($brand->categories->count() > 0)
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-3 text-left text-xs font-black text-gray-500">KATEGORI</th>
                                        <th class="p-3 text-right text-xs font-black text-gray-500">ORDER</th>
                                        <th class="p-3 text-right text-xs font-black text-gray-500">QTY</th>
                                        <th class="p-3 text-right text-xs font-black text-gray-500">PENDAPATAN</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($brand->categories as $cat)
                                        <tr class="hover:bg-white">
                                            <td class="p-3 font-medium">
                                                <span class="inline-flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                                                    {{ $cat->name }}
                                                </span>
                                            </td>
                                            <td class="p-3 text-right font-bold">{{ $cat->total_orders }}</td>
                                            <td class="p-3 text-right">{{ $cat->total_qty }}</td>
                                            <td class="p-3 text-right font-black text-green-600">
                                                Rp {{ number_format($cat->total_pendapatan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-white">
                                    <tr>
                                        <td class="p-3 font-black text-gray-700">TOTAL</td>
                                        <td class="p-3 text-right font-black">{{ $brand->total_orders }}</td>
                                        <td class="p-3 text-right font-black">{{ $brand->total_qty }}</td>
                                        <td class="p-3 text-right font-black text-green-600">Rp
                                            {{ number_format($brand->total_pendapatan, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="p-4 text-center text-gray-400 text-sm">Belum ada penjualan</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-400">Belum ada data penjualan</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

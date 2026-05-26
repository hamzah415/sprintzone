@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid py-4">
        <h2 class="text-2xl md:text-3xl font-black italic uppercase tracking-tight">
            LAPORAN <span class="text-orange-500">PENJUALAN</span>
        </h2>

        {{-- FILTER OPSI --}}
        <div class="flex gap-2 mb-4">
            <a href="{{ route('laporan.penjualan', ['filter' => 'today']) }}"
                class="px-4 py-2 rounded-lg text-xs font-black uppercase 
            {{ $filter == 'today' ? 'bg-orange-600 text-white' : 'bg-gray-100' }}">
                HARI INI
            </a>
            <a href="{{ route('laporan.penjualan', ['filter' => 'week']) }}"
                class="px-4 py-2 rounded-lg text-xs font-black uppercase 
            {{ $filter == 'week' ? 'bg-orange-600 text-white' : 'bg-gray-100' }}">
                MINGGU INI
            </a>
            <a href="{{ route('laporan.penjualan', ['filter' => 'month']) }}"
                class="px-4 py-2 rounded-lg text-xs font-black uppercase 
            {{ $filter == 'month' ? 'bg-orange-600 text-white' : 'bg-gray-100' }}">
                BULAN INI
            </a>
            <a href="{{ route('laporan.penjualan', ['filter' => 'all']) }}"
                class="px-4 py-2 rounded-lg text-xs font-black uppercase 
            {{ $filter == 'all' ? 'bg-orange-600 text-white' : 'bg-gray-100' }}">
                SEMUA
            </a>

            {{-- EXPORT BUTTON --}}
            <a href="{{ route('laporan.penjualan.export', ['filter' => $filter]) }}"
                class="ml-auto px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-black uppercase">
                <i class="fas fa-download mr-1"></i> EXPORT CSV
            </a>
        </div>

        {{-- STATS DENGAN PROFIT --}}
        <div class="grid grid-cols-4 gap-4 mb-4">
            <div class="bg-blue-500 text-white p-4 rounded-lg">
                <p class="text-xs uppercase">Total Order</p>
                <p class="text-2xl font-black">{{ $totalOrder ?? 0 }}</p>
            </div>
            <div class="bg-green-600 text-white p-4 rounded-lg">
                <p class="text-xs uppercase">Pendapatan</p>
                <p class="text-2xl font-black">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-500 text-white p-4 rounded-lg">
                <p class="text-xs uppercase">Est. Modal</p>
                <p class="text-2xl font-black">Rp {{ number_format(($totalPendapatan ?? 0) * 0.8, 0, ',', '.') }}</p>
                <p class="text-[10px] opacity-70">~80% estimasi</p>
            </div>
            <div class="bg-orange-500 text-white p-4 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase">Est. Profit</p>
                        <p class="text-2xl font-black">Rp {{ number_format(($totalPendapatan ?? 0) * 0.2, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <p class="text-[10px] opacity-70">~20% estimasi</p>
            </div>
        </div>

        {{-- FORM CARI MANUAL --}}
        <div class="card mt-4">
            <div class="card-body p-6">
                <form action="{{ route('laporan.penjualan.report') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                                Tanggal Mulai
                            </label>
                            <input type="date" name="start_date"
                                class="w-full border-2 border-gray-200 rounded-lg text-sm p-3">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                                Tanggal Akhir
                            </label>
                            <input type="date" name="end_date"
                                class="w-full border-2 border-gray-200 rounded-lg text-sm p-3">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="w-full bg-orange-600 text-white py-3 rounded-lg font-black uppercase text-sm">
                                <i class="fas fa-search mr-2"></i> CARI
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE HASIL --}}
        @if (isset($orders))
            <div class="card mt-4">
                <div class="table-responsive">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-left text-xs font-bold">#</th>
                                <th class="p-4 text-left text-xs font-bold">Tanggal</th>
                                <th class="p-4 text-left text-xs font-bold">Customer</th>
                                <th class="p-4 text-left text-xs font-bold">Produk</th>
                                <th class="p-4 text-left text-xs font-bold">Variant</th>
                                <th class="p-4 text-left text-xs font-bold">Qty</th>
                                <th class="p-4 text-left text-xs font-bold">Status</th>
                                <th class="p-4 text-left text-xs font-bold">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="border-t">
                                    <td class="p-4">#{{ $order->id }}</td>
                                    <td class="p-4">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="p-4">{{ $order->customer_name ?? '-' }}</td>
                                    <td class="p-4">
                                        @foreach ($order->items as $item)
                                            <div class="text-xs font-bold">{{ $item->variant->product->name ?? '-' }}</div>
                                        @endforeach
                                    </td>
                                    <td class="p-4">
                                        @foreach ($order->items as $item)
                                            <div class="text-xs">{{ $item->variant->color ?? '-' }} /
                                                {{ $item->variant->size ?? '-' }}</div>
                                        @endforeach
                                    </td>
                                    <td class="p-4">
                                        @foreach ($order->items as $item)
                                            <div class="text-xs">{{ $item->qty }}</div>
                                        @endforeach
                                    </td>
                                    <td class="p-4">
                                        <span
                                            class="px-2 py-1 text-xs rounded 
                                    @if ($order->status == 'success') bg-green-100 text-green-600
                                    @else bg-yellow-100 text-yellow-600 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

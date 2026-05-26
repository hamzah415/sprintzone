{{-- report.blade.php --}}
@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-black">LAPORAN PENJUALAN</h2>
        <div class="flex gap-2">
            <a href="{{ route('laporan.penjualan') }}" class="px-4 py-2 border rounded">KEMBALI</a>
            <a href="{{ route('laporan.penjualan.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                class="px-4 py-2 bg-green-600 text-white rounded">EXPORT</a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="bg-blue-500 text-white p-4 rounded">
            <p class="text-xs uppercase">Total Order</p>
            <p class="text-2xl font-black">{{ $totalOrder }}</p>
        </div>
        <div class="bg-green-600 text-white p-4 rounded">
            <p class="text-xs uppercase">Pendapatan</p>
            <p class="text-2xl font-black">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-orange-500 text-white p-4 rounded">
            <p class="text-xs uppercase">Items Terjual</p>
            <p class="text-2xl font-black">{{ $totalQuantity }}</p>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card">
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
                        <td class="p-4">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="p-4">{{ $order->customer_name ?? $order->user->name ?? 'Guest' }}</td>
                        <td class="p-4">
                            @foreach($order->items as $item)
                                <div class="text-xs font-bold">
                                    {{ $item->variant->product->name ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                        <td class="p-4">
                            @foreach($order->items as $item)
                                <div class="text-xs">
                                    {{ $item->variant->color ?? '-' }} / {{ $item->variant->size ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                        <td class="p-4">
                            @foreach($order->items as $item)
                                <div class="text-xs">{{ $item->qty }}</div>
                            @endforeach
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 text-xs rounded 
                                @if($order->status == 'completed') bg-green-100 text-green-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-4 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="p-8 text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
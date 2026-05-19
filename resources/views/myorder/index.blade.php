@extends('layouts.app')

@section('title', 'My Order')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold">My Order</h1>
        <p class="text-gray-500 text-sm">Riwayat pesananmu</p>
    </div>

    @forelse ($orders as $order)
    
        <div class="border border-gray-200 rounded-xl mb-4 overflow-hidden">
            
            {{-- HEADER --}}
            <div class="flex items-center justify-between px-4 py-3 bg-gray-50">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-bold">#{{ $order->id }}</span>
                    <span class="text-sm text-gray-400">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <span class="px-2 py-1 rounded text-xs font-bold uppercase
                    {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                       ($order->status == 'success' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">
                    {{ $order->status }}
                </span>
            </div>

            {{-- ITEMS --}}
            <div class="p-4 space-y-3">
                @foreach ($order->items as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100"></div>
                            @endif
                            <div>
                                <div class="text-sm font-medium">{{ $item->product->name }}</div>
                                <div class="text-xs text-gray-400">Qty: {{ $item->qty }}</div>
                            </div>
                        </div>
                        <div class="text-sm font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>

            {{-- TOTAL --}}
            <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 bg-gray-50">
                <span class="text-sm font-bold text-gray-500">Total</span>
                <span class="text-lg font-bold text-orange-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

        </div>

    @empty

        <div class="text-center py-16 text-gray-400">
            <p>Belum ada pesanan</p>
            <a href="{{ route('products.etalase') }}" class="text-orange-500 hover:underline text-sm mt-2 inline-block">
                Belanja sekarang
            </a>
        </div>

    @endforelse

</div>

@endsection
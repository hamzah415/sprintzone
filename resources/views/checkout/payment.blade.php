@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="grid md:grid-cols-2 gap-8">

        {{-- KIRI: RINGKASAN PESANAN --}}
        <div class="bg-white border rounded-2xl overflow-hidden">
            
            <div class="bg-gray-50 px-5 py-4 border-b">
                <h2 class="font-bold text-sm">Ringkasan Pesanan</h2>
            </div>

            <div class="p-5 space-y-4">
                @php $items = \App\Models\OrderItem::where('order_id', $order->id)->with('product')->get(); @endphp
                
                @foreach($items as $item)
                    <div class="flex items-center gap-3">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-14 h-14 rounded-lg object-cover">
                        @else
                            <div class="w-14 h-14 rounded-lg bg-gray-100"></div>
                        @endif
                        <div class="flex-1">
                            <div class="text-sm font-medium">{{ $item->product->name }}</div>
                            <div class="text-xs text-gray-400">Qty: {{ $item->qty }}</div>
                        </div>
                        <div class="text-sm font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="px-5 py-4 bg-gray-50 border-t flex justify-between">
                <span class="font-bold">Total</span>
                <span class="text-xl font-bold text-orange-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- KANAN: PEMBAYARAN --}}
        <div class="bg-white border rounded-2xl overflow-hidden">
            
            <div class="bg-black px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-white text-sm font-bold uppercase">Pembayaran</h2>
                        <p class="text-gray-400 text-xs">Order #{{ $order->id }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 flex flex-col items-center justify-center text-center">
                {{-- ICON CART/PAYMENT --}}
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3zm7-3v2m0 4v2m0 4h2m-4-8H7"></path>
                    </svg>
                </div>
                
                <h3 class="font-bold text-lg mb-2">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h3>
                <p class="text-gray-500 text-sm mb-6">Klik tombol di bawah untuk memilih metode pembayaran</p>
                
                <button id="pay-button" 
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9l-5 5-5-5m6 8V5"></path>
                    </svg>
                    Bayar Sekarang
                </button>
                
                <p class="text-xs text-gray-400 mt-4">
                    Secure payment via Midtrans
                </p>
            </div>

        </div>

    </div>

    <div class="mt-6 text-center">
        <a href="/My-Order" class="text-gray-400 hover:text-orange-500 text-sm">
            ← Kembali ke Pesanan
        </a>
    </div>

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const snapToken = '{{ $snapToken }}';
    
    document.getElementById('pay-button').addEventListener('click', function() {
        snap.pay(snapToken, {
            onSuccess: function(result) {
                handlePaymentSuccess(result);
            },
            onPending: function(result) {
                alert('Menunggu pembayaran...');
            },
            onError: function(result) {
                alert('Pembayaran gagal');
            }
        });
    });
});

async function handlePaymentSuccess(result) {
    try {
        const response = await fetch('/payment/success/{{ $order->id }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (response.ok) {
            window.location.href = '/My-Order?success=true';
        }
    } catch (error) {
        window.location.href = '/My-Order';
    }
}
</script>

@endsection
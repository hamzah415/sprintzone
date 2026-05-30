@extends('layouts.app')

@section('title', 'Payment')

@section('content')
    <div class="w-full px-4 lg:px-10 py-10 bg-gray-50 min-h-screen">
        
        {{-- GRID: KIRI LEBIH BESAR --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- KIRI: RINGKASAN PESANAN (LEBIH BESAR) --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm">
                <div class="bg-gray-50 px-6 py-5 border-b">
                    <h2 class="font-bold text-lg">Ringkasan Pesanan</h2>
                </div>

                <div class="p-7 space-y-5">
                    @php
                        $items = \App\Models\OrderItem::where('order_id', $order->id)
                            ->with(['product', 'variant'])
                            ->get();
                    @endphp

                    @forelse ($items as $item)
                        @php
                            $image = $item->product->image ?? null;
                            if ($item->variant) {
                                $image = $item->variant->image ?? $image;
                            }
                        @endphp

                        <div class="flex items-center gap-4">
                            {{-- IMAGE --}}
                            @if ($image)
                                <img src="{{ asset('storage/' . $image) }}" class="w-24 h-24 rounded-2xl object-cover border">
                            @else
                                <div class="w-24 h-24 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            {{-- INFO --}}
                            <div class="flex-1">
                                <div class="text-lg font-semibold text-gray-800">
                                    {{ $item->product->name }}
                                </div>
                                @if ($item->variant)
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $item->variant->color }} / {{ $item->variant->size }} × {{ $item->qty }}
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500 mt-1">Qty: {{ $item->qty }}</div>
                                @endif
                            </div>

                            {{-- PRICE --}}
                            <div class="text-xl font-bold text-orange-600">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-4">Tidak ada item</div>
                    @endforelse
                </div>

                {{-- TOTAL --}}
                <div class="px-6 py-5 bg-gray-50 border-t flex justify-between items-center">
                    <span class="text-xl font-bold">Total</span>
                    <span class="text-3xl font-black text-orange-600">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- KANAN: PEMBAYARAN (LEBIH KECIL) --}}
            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm sticky top-24">
                {{-- HEADER --}}
                <div class="bg-black px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-white text-lg font-bold uppercase">Pembayaran</h2>
                            <p class="text-gray-400 text-sm">Order #{{ $order->id }}</p>
                        </div>
                    </div>
                </div>

                {{-- TIMER --}}
                <div class="px-6 py-3 bg-red-50 border-b border-red-100 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-red-600 font-bold text-sm">Batas Waktu:</span>
                    <span id="countdown" class="text-red-600 font-black text-lg">01:00:00</span>
                </div>

                {{-- CONTENT --}}
                <div class="p-8 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9l-5 5-5-5m6 8V5"></path>
                        </svg>
                    </div>

                    <h3 class="font-black text-2xl text-gray-900 mb-2">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </h3>

                    <p class="text-gray-500 text-sm mb-6">
                        Klik tombol di bawah untuk memilih metode pembayaran
                    </p>

                    {{-- BUTTON --}}
                    <button id="pay-button" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9l-5 5-5-5m6 8V5"></path>
                        </svg>
                        Bayar Sekarang
                    </button>

                    <p class="text-xs text-gray-400 mt-4">Secure payment via Midtrans</p>
                </div>
            </div>

        </div>

        {{-- BACK --}}
        <div class="mt-8 text-center">
            <a href="/My-Order" class="text-gray-500 hover:text-orange-500 text-sm font-medium transition">
                ← Kembali ke Pesanan
            </a>
        </div>
    </div>

    {{-- MIDTRANS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        // Timer configuration (1 jam dalam detik)
        const EXPIRY_SECONDS = 3600; // 1 jam
        let timeRemaining = {{ $order->expires_at ? \Carbon\Carbon::now()->diffInSeconds($order->expires_at) : 3600 }};
        
        // Format waktu ke HH:MM:SS
        function formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            const h = hours.toString().padStart(2, '0');
            const m = minutes.toString().padStart(2, '0');
            const s = secs.toString().padStart(2, '0');
            
            return `${h}:${m}:${s}`;
        }

        // Update tampilan timer
        const countdownEl = document.getElementById('countdown');
        countdownEl.textContent = formatTime(timeRemaining);

        // Timer interval
        const timerInterval = setInterval(function() {
            timeRemaining--;
            
            countdownEl.textContent = formatTime(timeRemaining);
            
            // Jika waktu habis
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                cancelOrder();
            }
        }, 1000);

        // Fungsi cancel order
        async function cancelOrder() {
            try {
                const response = await fetch('/payment/cancel/{{ $order->id }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    alert(' Waktu pembayaran telah expire. Pesanan dibatalkan.');
                    window.location.href = '/My-Order?cancelled=true';
                } else {
                    window.location.href = '/My-Order';
                }
            } catch (error) {
                console.error('Error:', error);
                window.location.href = '/My-Order';
            }
        }

        // Payment button handler
        document.addEventListener('DOMContentLoaded', function() {
            const snapToken = '{{ $snapToken }}';
            const orderId = '{{ $order->id }}';

            document.getElementById('pay-button').addEventListener('click', function() {
                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        // Hentikan timer jika pembayaran berhasil
                        clearInterval(timerInterval);
                        handlePaymentSuccess(result, orderId);
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

        async function handlePaymentSuccess(result, orderId) {
            try {
                const response = await fetch('/payment/success/' + orderId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    window.location.href = '/My-Order?success=true';
                } else {
                    window.location.href = '/My-Order';
                }
            } catch (error) {
                console.error('Error:', error);
                window.location.href = '/My-Order';
            }
        }
    </script>
@endsection
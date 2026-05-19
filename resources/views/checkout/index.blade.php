@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">Checkout</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- KIRI: DATA (FORM 1: SAVE) --}}
        <div class="bg-white border rounded-xl p-5 order-2 md:order-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-sm">Data Penerima</h2>
                @if(auth()->user()->phone && auth()->user()->address)
                    <span class="text-[10px] text-green-600">✓ Tersimpan</span>
                @endif
            </div>
            
            <form action="{{ route('user.profile.save') }}" method="POST" class="space-y-3">
                @csrf
                
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Nama</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="text-xs text-gray-500 mb-1 block">WhatsApp</label>
                    <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="0812..."
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Alamat</label>
                    <textarea name="address" rows="2" placeholder="JL, Kota"
                        class="w-full border rounded-lg px-3 py-2 text-sm resize-none">{{ auth()->user()->address ?? '' }}</textarea>
                </div>

                <button type="submit"
                    class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2 rounded-lg text-sm transition">
                    Simpan Data
                </button>
            </form>
        </div>

        {{-- KANAN: CHECKOUT (FORM 2: CHECKOUT SAJA) --}}
        <div class="bg-white border rounded-xl p-5 order-1 md:order-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-sm">Pesanan ({{ count($cart) }})</h2>
            </div>
            
            @php $total = 0; @endphp
            
            <div class="space-y-3">
                @foreach ($cart as $item)
                    @php $price = $item->product->discount_price ?? $item->product->price; $subtotal = $price * $item->qty; $total += $subtotal; @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100"></div>
                            @endif
                            <div class="text-sm">{{ $item->product->name }}</div>
                        </div>
                        <div class="text-sm font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
            
            <div class="flex items-center justify-between mt-4 pt-3 border-t">
                <span class="font-bold">Total</span>
                <span class="font-bold text-lg text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            @if(auth()->user()->phone && auth()->user()->address)
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="customer_name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="phone" value="{{ auth()->user()->phone }}">
                    <input type="hidden" name="address" value="{{ auth()->user()->address }}">
                    
                    <button type="submit"
                        class="w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white py-2.5 rounded-lg font-medium text-sm transition">
                        Checkout Sekarang
                    </button>
                </form>
            @else
                <div class="mt-4 text-xs text-gray-500 text-center py-2 bg-gray-100 rounded-lg">
                    ⚠️ Simpan data di kiri dulu
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
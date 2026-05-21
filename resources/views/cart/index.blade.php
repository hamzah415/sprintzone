@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-4xl font-black italic uppercase mb-10">Your Cart</h1>

        @if (count($cart) > 0)
            <div class="space-y-6">
                @php $total = 0; @endphp

                @foreach ($cart as $item)
                    @php
                        $image = $item->product->image;
                        if (!$image && $item->variant) {
                            $image = $item->variant->image;
                        }

                        $price = $item->product->price ?? 0;
                        $discount = $item->product->discount_price ?? null;

                        if ($item->size) {
                            $price = $item->size->price ?? $price;
                        } elseif ($item->variant) {
                            $price = $item->variant->price ?? $price;
                            $discount = $item->variant->discount_price ?? $discount;
                        }

                        $effectivePrice = $discount ?? $price;
                        $subtotal = $effectivePrice * $item->qty;
                        $total += $subtotal;

                        $color = $item->variant?->color ?? null;
                    @endphp

                    <div class="flex items-center justify-between border rounded-2xl p-4">
                        <div class="flex items-center gap-4">
                            @if ($image)
                                <img src="{{ asset('storage/' . $image) }}" class="w-24 h-24 rounded-xl object-cover">
                            @else
                                <div class="w-24 h-24 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                    No Image</div>
                            @endif

                            <div>
                                <h2 class="font-black text-lg">{{ $item->product->name }}</h2>

                                <div class="flex items-center gap-4 mt-2 text-sm">
                                    {{-- Warna --}}
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500">Warna:</span>
                                        @if ($color)
                                            <span class="w-5 h-5 rounded-full border border-gray-300"
                                                style="background-color: {{ strtolower($color) }}"></span>
                                            <span class="font-medium text-gray-700 uppercase">{{ $color }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>

                                    {{-- Ukuran --}}
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500">Ukuran:</span>
                                        @if ($item->size)
                                            <span
                                                class="px-2 py-0.5 bg-gray-100 border border-gray-200 rounded text-xs font-medium">{{ $item->size->size }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 mt-3">
                                    <form action="{{ route('cart.decrease', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-black hover:text-white font-black transition">-</button>
                                    </form>
                                    <span class="font-black text-sm min-w-[20px] text-center">{{ $item->qty }}</span>
                                    <form action="{{ route('cart.increase', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-8 h-8 rounded-full bg-orange-500 hover:bg-orange-600 text-white font-black transition">+</button>
                                    </form>
                                </div>

                                <div class="font-black mt-2">
                                    @if ($discount && $discount < $price)
                                        <span class="text-orange-600">Rp {{ number_format($discount, 0, ',', '.') }}</span>
                                        <span class="text-gray-400 text-sm line-through ml-2">Rp
                                            {{ number_format($price, 0, ',', '.') }}</span>
                                    @else
                                        <span>Rp {{ number_format($price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-bold">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-right">
                <div class="text-2xl font-black">Total: Rp {{ number_format($total, 0, ',', '.') }}</div>
            </div>

            <div class="flex items-center justify-end mt-8">
                <a href="{{ route('checkout.index') }}"
                    class="bg-orange-500 text-white px-6 py-3 rounded-xl font-black uppercase">Checkout</a>
            </div>
        @else
            <div class="text-center py-20 text-gray-400">Cart is empty</div>
        @endif
    </div>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}
        </div>
    @endif
@endsection

@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')

    <div class="max-w-5xl mx-auto px-4 py-10">

        <h1 class="text-4xl font-black italic uppercase mb-10">
            Your Cart
        </h1>

        @if (count($cart) > 0)

            <div class="space-y-6">

                @php $total = 0; @endphp

                @foreach ($cart as $item)
                    @php
                        $subtotal = $item->product->discount_price ?? $item->product->price * $item->qty;
                        $total += $subtotal;
                    @endphp

                    <div class="flex items-center justify-between border rounded-2xl p-4">

                        <div class="flex items-center gap-4">

                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                class="w-24 h-24 rounded-xl object-cover">

                            <div>

                                <h2 class="font-black text-lg">
                                    {{ $item->product->name }}
                                </h2>

                                <div class="flex items-center gap-3 mt-3">

                                    {{-- MINUS --}}
                                    <form action="{{ route('cart.decrease', $item->product_id) }}" method="POST">
                                        @csrf

                                        <button type="submit"
                                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-black hover:text-white font-black transition">

                                            -

                                        </button>
                                    </form>

                                    {{-- QTY --}}
                                    <span class="font-black text-sm min-w-[20px] text-center">

                                        {{ $item->qty }}

                                    </span>

                                    {{-- PLUS --}}
                                    <form action="{{ route('cart.increase', $item->product_id) }}" method="POST">
                                        @csrf

                                        <button type="submit"
                                            class="w-8 h-8 rounded-full bg-orange-500 hover:bg-orange-600 text-white font-black transition">

                                            +

                                        </button>
                                    </form>

                                </div>

                                <div class="font-black mt-2">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </div>

                            </div>

                        </div>

                        <form action="{{ route('cart.remove', $item->product_id) }}" method="POST">

                            @csrf

                            <button type="submit" class="text-red-500 font-bold">

                                Remove

                            </button>

                        </form>

                    </div>
                @endforeach

            </div>

            <div class="mt-10 text-right">

                <div class="text-2xl font-black">
                    Total:
                    Rp {{ number_format($total, 0, ',', '.') }}
                </div>

            </div>

            <div class="flex items-center justify-end mt-8">
                <a href="{{ route('checkout.index') }}"
                    class="bg-orange-500 text-white px-6 py-3 rounded-xl font-black uppercase">

                    Checkout

                </a>
            </div>
        @else
            <div class="text-center py-20 text-gray-400">
                Cart is empty
            </div>

        @endif

    </div>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif


@endsection

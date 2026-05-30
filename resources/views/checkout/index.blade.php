@extends('layouts.app')

@section('content')
    <div class="w-full px-4 lg:px-10 py-8 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Checkout
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Lengkapi data penerima dan selesaikan pesanan Anda
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-6">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- LAYOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT CONTENT --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- DATA PENERIMA --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-6">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="font-bold text-lg">
                                Data Penerima
                            </h2>
                            <p class="text-sm text-gray-500">
                                Pastikan data pengiriman sudah benar
                            </p>
                        </div>

                        @if (auth()->user()->phone && auth()->user()->address)
                            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">
                                ✓ Tersimpan
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('user.profile.save') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">
                                Nama Lengkap
                            </label>

                            <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">
                                Nomor WhatsApp
                            </label>

                            <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                                placeholder="0812xxxx"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">
                                Alamat Lengkap
                            </label>

                            <textarea name="address" rows="4" placeholder="Jl, Kecamatan, Kota, Provinsi"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">{{ auth()->user()->address ?? '' }}</textarea>
                        </div>

                        <button type="submit"
                            class="bg-black hover:bg-orange-600 text-white px-6 py-3 rounded-xl text-sm font-semibold transition">
                            Simpan Data
                        </button>
                    </form>
                </div>

            </div>

            {{-- RIGHT SIDEBAR --}}
            <div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 sticky top-24">

                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-bold text-lg">
                            Ringkasan Pesanan
                        </h2>

                        <span class="text-sm text-gray-500">
                            {{ count($cart) }} Item
                        </span>
                    </div>

                    @php $total = 0; @endphp

                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">

                        @foreach ($cart as $item)
                            @php
                                $price = $item->product->price ?? 0;
                                $discount = $item->product->discount_price ?? null;
                                $image = $item->product->image;

                                if ($item->variant) {
                                    $price = $item->variant->price ?? $price;
                                    $discount = $item->variant->discount_price ?? $discount;
                                    $image = $item->variant->image ?? $image;
                                }

                                $effectivePrice = $discount ?? $price;
                                $subtotal = $effectivePrice * $item->qty;
                                $total += $subtotal;
                            @endphp

                            <div class="flex gap-3">

                                {{-- IMAGE --}}
                                @if ($image)
                                    <img src="{{ asset('storage/' . $image) }}"
                                        class="w-16 h-16 rounded-xl object-cover border">
                                @else
                                    <div class="w-16 h-16 rounded-xl bg-gray-100"></div>
                                @endif

                                {{-- INFO --}}
                                <div class="flex-1">

                                    <div class="text-sm font-semibold text-gray-800 leading-snug">
                                        {{ $item->product->name }}
                                    </div>

                                    @if ($item->variant)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $item->variant->color }}
                                            /
                                            {{ $item->variant->size }}
                                        </div>
                                    @endif

                                    <div class="text-xs text-gray-500 mt-1">
                                        Qty: {{ $item->qty }}
                                    </div>

                                    <div class="text-sm font-bold text-orange-600 mt-2">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>

                    {{-- TOTAL --}}
                    <div class="border-t pt-5 mt-5">

                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-500">
                                Subtotal
                            </span>

                            <span class="text-sm font-medium">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold">
                                Total
                            </span>

                            <span class="text-2xl font-black text-orange-600">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                    </div>

                    {{-- BUTTON --}}
                    @if (auth()->user()->phone && auth()->user()->address)
                        <form action="{{ route('checkout.store') }}" method="POST" class="mt-6">
                            @csrf

                            <input type="hidden" name="customer_name" value="{{ auth()->user()->name }}">
                            <input type="hidden" name="phone" value="{{ auth()->user()->phone }}">
                            <input type="hidden" name="address" value="{{ auth()->user()->address }}">

                            <button type="submit"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-xl font-bold transition">
                                Checkout Sekarang
                            </button>
                        </form>
                    @else
                        <div
                            class="mt-6 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm rounded-xl px-4 py-3 text-center">
                            ⚠️ Lengkapi data penerima terlebih dahulu
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
@endsection

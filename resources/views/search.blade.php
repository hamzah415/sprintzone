@extends('layouts.app')

@section('title', 'Search Result')

@section('content')
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter">Search <span
                        class="text-orange-500">Result</span></h2>
                <p class="text-gray-500 font-medium"> "{{ $query }}" - {{ $products->count() }} produk ditemukan</p>
            </div>

            {{-- SORT --}}
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase">Urutkan:</span>
                <div class="flex gap-1">
                    <a href="{{ route('search', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                        class="px-3 py-1.5 text-[10px] font-bold rounded-md transition-all {{ request('sort', 'latest') == 'latest' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Terbaru
                    </a>
                    <a href="{{ route('search', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}"
                        class="px-3 py-1.5 text-[10px] font-bold rounded-md transition-all {{ request('sort') == 'price_low' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        ⬇ Termurah
                    </a>
                    <a href="{{ route('search', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}"
                        class="px-3 py-1.5 text-[10px] font-bold rounded-md transition-all {{ request('sort') == 'price_high' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        ⬆ Termahal
                    </a>
                </div>
            </div>
        </div>

        {{-- BACK LINK --}}
        <div class="mb-6">
            <a href="{{ route('products.etalase') }}" class="text-orange-500 hover:underline text-sm">
                ← Kembali ke Etalase
            </a>
        </div>

        {{-- PRODUCT GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            @forelse($products as $product)
                <div class="group product-item" data-product-id="{{ $product->id }}">
                    <a href="{{ route('products.show', $product->id) }}" class="block no-underline hover:no-underline">
                        <div class="relative aspect-square overflow-hidden rounded-2xl bg-gray-100 mb-4">
                            @php
                                // CEK GAMBAR: variant dulu
                                $productImage = $product->image;
                                if (!$productImage && $product->variants->isNotEmpty()) {
                                    $variantWithImage = $product->variants->firstWhere(fn($v) => !empty($v['image']));
                                    if ($variantWithImage) {
                                        $productImage = $variantWithImage['image'];
                                    }
                                }
                            @endphp

                            @if ($productImage)
                                <img src="{{ asset('storage/' . $productImage) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No Image
                                </div>
                            @endif

                            @php
                                // CEK DISCOUNT
                                $minPrice = $product->price ?? 0;
                                $minDiscount = $product->discount_price ?? null;
                                if (!$minPrice && $product->variants->isNotEmpty()) {
                                    $vp = $product->variants->pluck('price')->filter();
                                    $vd = $product->variants->pluck('discount_price')->filter();
                                    if ($vp->isNotEmpty()) {
                                        $minPrice = $vp->min();
                                    }
                                    if ($vd->isNotEmpty()) {
                                        $minDiscount = $vd->min();
                                    }
                                }
                                $hasDiscount = $minDiscount && $minDiscount < $minPrice;
                            @endphp

                            @if ($hasDiscount)
                                <div class="absolute top-3 right-3">
                                    <span
                                        class="bg-red-600 text-white text-[9px] font-black px-2 py-1 uppercase italic rounded">Sale</span>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-1">
                            <div class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">
                                {{ $product->brand->name ?? 'Brand' }}
                            </div>
                            <h3 class="font-bold text-sm text-gray-800 leading-tight">
                                {{ $product->name }}
                            </h3>

                            @if ($hasDiscount)
                                <div class="flex items-center gap-2">
                                    <div class="text-lg font-black italic tracking-tighter text-gray-900">
                                        Rp {{ number_format($minDiscount, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 line-through">
                                        Rp {{ number_format($minPrice, 0, ',', '.') }}
                                    </div>
                                </div>
                            @else
                                <div class="text-lg font-black italic tracking-tighter text-gray-900">
                                    Rp {{ number_format($minPrice, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </a>

                    @auth
                        <button type="button"
                            onclick="handleAddToCart({{ $product->id }}, '{{ str_replace("'", "\\'", $product->name) }}')"
                            class="w-full mt-4 bg-gray-100 group-hover:bg-orange-600 group-hover:text-white transition-colors py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                            Add to Cart
                        </button>
                    @else
                        <a href="{{ route('login') }}"
                            class="block w-full mt-4 text-center bg-gray-100 hover:bg-orange-600 hover:text-white transition-colors py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                            Add to Cart
                        </a>
                    @endauth
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-gray-400 font-bold">Produk tidak ditemukan</p>
                        <a href="{{ route('products.etalase') }}" class="text-orange-500 hover:underline text-sm mt-2">
                            Lihat semua produk
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if ($products->hasPages())
            <div class="mt-16 flex justify-center gap-2">
                @if ($products->onFirstPage())
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold text-gray-300"
                        disabled>‹</button>
                @else
                    <a href="{{ $products->previousPageUrl() }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold hover:bg-black hover:text-white transition">‹</a>
                @endif

                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    @if ($i >= $products->currentPage() - 1 && $i <= $products->currentPage() + 1)
                        @if ($i == $products->currentPage())
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg bg-black text-white text-xs font-bold">{{ $i }}</button>
                        @else
                            <a href="{{ $products->url($i) }}"
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold hover:bg-black hover:text-white transition">{{ $i }}</a>
                        @endif
                    @endif
                @endfor

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold hover:bg-black hover:text-white transition">›</a>
                @else
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold text-gray-300"
                        disabled>›</button>
                @endif
            </div>
        @endif

    </div>
@endsection

@extends('layouts.app')

@section('title', 'Etalase Produk')

@section('content')
<div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h2 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter">Most <span class="text-orange-500">Wanted</span></h2>
            <p class="text-gray-500 font-medium">No limits. Just your best performance ever.</p>
        </div>

        {{-- SORT LINKS (Tanpa Form) --}}
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold text-gray-400 uppercase">Urutkan:</span>
            <div class="flex gap-1">
                <a href="{{ route('products.etalase', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                    class="px-3 py-1.5 text-[10px] font-bold rounded-md transition-all {{ request('sort', 'latest') == 'latest' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Terbaru
                </a>
                <a href="{{ route('products.etalase', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}"
                    class="px-3 py-1.5 text-[10px] font-bold rounded-md transition-all {{ request('sort') == 'price_low' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    ⬇ Termurah
                </a>
                <a href="{{ route('products.etalase', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}"
                    class="px-3 py-1.5 text-[10px] font-bold rounded-md transition-all {{ request('sort') == 'price_high' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    ⬆ Termahal
                </a>
            </div>
        </div>
    </div>

    {{-- BREADCRUMB FILTER --}}
    @if(request()->has('brand'))
        @php
            $currentBrand = \App\Models\Brand::where('slug', request('brand'))->first();
        @endphp
        @if($currentBrand)
            <div class="mb-6 flex items-center gap-2">
                <span class="text-sm text-gray-500">Filter:</span>
                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">
                    {{ $currentBrand->name }}
                    <a href="{{ route('products.etalase', request()->except('brand')) }}" class="ml-2 text-orange-500 hover:text-orange-700">×</a>
                </span>
            </div>
        @endif
    @endif

    {{-- PRODUCT GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
        @forelse($products as $product)
            <div class="group">
                <a href="{{ route('products.show', $product->id) }}" class="block no-underline hover:no-underline">
                    <div class="relative aspect-square overflow-hidden rounded-2xl bg-gray-100 mb-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No Image</div>
                        @endif
                        @if($product->discount_price)
                            <div class="absolute top-3 right-3">
                                <span class="bg-red-600 text-white text-[9px] font-black px-2 py-1 uppercase italic rounded">Sale</span>
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
                        @if($product->discount_price)
                            <div class="flex items-center gap-2">
                                <div class="text-lg font-black italic tracking-tighter text-gray-900">
                                    Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-gray-400 line-through">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                        @else
                            <div class="text-lg font-black italic tracking-tighter text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                    @auth
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full mt-4 bg-gray-100 group-hover:bg-orange-600 group-hover:text-white transition-colors py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full mt-4 text-center bg-gray-100 hover:bg-orange-600 hover:text-white transition-colors py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                            Add to Cart
                        </a>
                    @endauth
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <p class="text-gray-400 font-bold">No Product Available</p>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($products->hasPages())
        <div class="mt-16 flex justify-center gap-2">
            @if($products->onFirstPage())
                <button class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold text-gray-300" disabled>‹</button>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold hover:bg-black hover:text-white transition">‹</a>
            @endif

            @for($i = 1; $i <= $products->lastPage(); $i++)
                @if($i >= $products->currentPage() - 1 && $i <= $products->currentPage() + 1)
                    @if($i == $products->currentPage())
                        <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-black text-white text-xs font-bold">{{ $i }}</button>
                    @else
                        <a href="{{ $products->url($i) }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold hover:bg-black hover:text-white transition">{{ $i }}</a>
                    @endif
                @endif
            @endfor

            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold hover:bg-black hover:text-white transition">›</a>
            @else
                <button class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold text-gray-300" disabled>›</button>
            @endif
        </div>
    @endif

</div>
@endsection
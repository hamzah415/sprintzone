@extends('layouts.app')

@section('title', 'Power Your Steps')

@section('styles')
    /* Animasi Sepatu Spesifik Home */
    .shoe-shadow {
    filter: drop-shadow(0 35px 35px rgba(0,0,0,0.25));
    transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .shoe-container:hover .shoe-image {
    transform: rotate(-10deg) scale(1.6);
    }
@endsection

@section('content')
    <main
        class="relative flex-1 flex flex-col md:flex-row items-center px-6 md:px-20 py-10 md:py-0 overflow-hidden bg-white">
        <div class="z-20 w-full md:w-1/2 text-center md:text-left order-1">
            <h2 class="text-5xl md:text-[7.5rem] font-black italic uppercase leading-[0.85] mb-6 tracking-tighter">
                Power Your <br><span class="text-orange-500 md:text-black">Steps</span>
            </h2>
            <p class="text-gray-500 text-sm md:text-lg max-w-sm mb-10 font-medium leading-relaxed mx-auto md:mx-0">
                Designed to support your every move, helping you go further, faster, and stronger every day.
            </p>
            <button @click="modalOpen = true"
                class="bg-[#444444] text-white px-10 md:px-16 py-4 rounded-md font-black uppercase text-xs md:text-sm tracking-[0.3em] shadow-xl hover:bg-black transition-all active:scale-95">
                Buy now
            </button>
        </div>

        <div
            class="shoe-container relative w-full md:w-1/2 h-[350px] md:h-full flex justify-center items-center mt-12 md:mt-0 order-2">
            <div class="absolute w-72 h-72 bg-orange-100 rounded-full blur-[110px] opacity-40 animate-pulse"></div>
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2070"
                class="shoe-image shoe-shadow relative z-10 w-full max-w-md md:max-w-4xl transform rotate-[-15deg] md:rotate-[-20deg] scale-110 md:scale-150 object-contain">
        </div>
    </main>



    {{-- HOME PRODUCTS --}}
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">

            <div>

                <div class="mb-4">

                    <h2 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter">

                        Trending
                        <span class="text-orange-500">
                            Now
                        </span>

                    </h2>

                    <p class="text-gray-500 font-medium">
                        No limits. Just your best performance ever.
                    </p>

                </div>

            </div>

        </div>

        {{-- PRODUCT GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">

            @forelse($products ?? [] as $product)
                <div class="group">

                    <a href="{{ route('products.show', $product->id) }}" class="block no-underline hover:no-underline">

                        {{-- IMAGE --}}
                        <div class="relative aspect-square overflow-hidden rounded-2xl bg-gray-100 mb-4">

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">

                                    No Image

                                </div>
                            @endif

                            {{-- STATUS --}}
                            <div class="absolute top-3 left-3">

                                @if ($product->status == 'active')
                                    <span
                                        class="bg-black text-white text-[9px] font-black px-2 py-1 uppercase italic rounded">

                                        New

                                    </span>
                                @else
                                    <span
                                        class="bg-gray-500 text-white text-[9px] font-black px-2 py-1 uppercase italic rounded">

                                        Inactive

                                    </span>
                                @endif

                            </div>

                            {{-- DISCOUNT --}}
                            @if ($product->discount_price)
                                <div class="absolute top-3 right-3">

                                    <span
                                        class="bg-red-600 text-white text-[9px] font-black px-2 py-1 uppercase italic rounded">

                                        Sale

                                    </span>

                                </div>
                            @endif

                        </div>

                        {{-- PRODUCT INFO --}}
                        <div class="space-y-1">

                            {{-- BRAND --}}
                            <div class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">

                                {{ $product->brand->name ?? 'No Brand' }}

                            </div>

                            {{-- PRODUCT NAME --}}
                            <h3 class="font-bold text-sm text-gray-800 leading-tight line-clamp-2">

                                {{ $product->name }}

                            </h3>

                            {{-- PRICE --}}
                            @if ($product->discount_price)
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

                        {{-- BUTTON --}}
                        @auth

                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf

                                <button type="submit"
                                    class="w-full mt-4 bg-gray-100 group-hover:bg-orange-600 group-hover:text-white transition-colors py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">

                                    Add to Cart

                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full mt-4 text-center hover:no-underline bg-gray-100 hover:bg-orange-600 hover:text-white transition-colors py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">

                                Add to Cart

                            </a>

                        @endauth
                    </a>
                </div>

            @empty

                <div class="col-span-full text-center py-20">

                    <div class="flex flex-col items-center justify-center">

                        <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>

                        <p class="text-gray-400 font-medium text-sm tracking-wide">

                            There is no product data yet.

                        </p>

                    </div>

                </div>
            @endforelse

        </div>

    </div>
@endsection

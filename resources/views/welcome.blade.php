{{-- =========================
SPRINTZONE PREMIUM HOME
========================= --}}

@extends('layouts.app')

@section('title', 'SprintZone - Fast Steps Big Impact')

@section('styles')

    body{
    background:#f7f7f7;
    }

    .hero-bg{
    background:
    radial-gradient(circle at top right, rgba(249,115,22,0.15), transparent 30%),
    linear-gradient(to right,#050505,#101010,#151515);
    }

    .shoe-shadow{
    filter: drop-shadow(0 40px 40px rgba(0,0,0,0.35));
    transition: all .7s cubic-bezier(0.4,0,0.2,1);
    }

    .shoe-container:hover .shoe-image{
    transform: rotate(-14deg) scale(1.6);
    }

    .shoe-image{
    transition: all .7s cubic-bezier(0.4,0,0.2,1);
    }

    .variant-btn.active{
    background:black;
    color:white;
    }

    .product-card{
    transition:all .4s ease;
    }

    .product-card:hover{
    transform:translateY(-10px);
    box-shadow:0 25px 50px rgba(0,0,0,0.08);
    }

    .product-image{
    transition:all .5s ease;
    }

    .product-card:hover .product-image{
    transform:scale(1.08) rotate(-3deg);
    }

    .banner-card{
    overflow:hidden;
    position:relative;
    }

    .banner-card img{
    transition:all .6s ease;
    }

    .banner-card:hover img{
    transform:scale(1.05);
    }

    .glass{
    background:rgba(255,255,255,0.7);
    backdrop-filter:blur(10px);
    }

@endsection

@section('content')

    {{-- ================= HERO ================= --}}
    <section class="hero-bg relative overflow-hidden min-h-[78vh] flex items-center">

        {{-- blur --}}
        <div class="absolute top-[-80px] right-[-80px] w-[320px] h-[320px] bg-orange-500/20 blur-[120px] rounded-full">
        </div>

        <div class="max-w-screen-2xl mx-auto w-full px-6 md:px-12 py-12">

            <div class="grid md:grid-cols-2 gap-8 items-center">

                {{-- LEFT --}}
                <div class="relative z-20">

                    <span
                        class="inline-flex items-center gap-2 bg-white/10 border border-white/10 text-white text-[10px] uppercase tracking-[0.25em] px-4 py-2 rounded-full font-black mb-6">
                        NEW ARRIVAL
                    </span>

                    <h1
                        class="text-white text-4xl md:text-[5.8rem] leading-[0.85] font-black italic uppercase tracking-[-0.08em]">

                        FAST <br>
                        STEPS <br>

                        <span class="text-orange-500">
                            BIG IMPACT
                        </span>

                    </h1>

                    <p class="text-gray-300 max-w-md mt-6 leading-relaxed text-sm">
                        Temukan koleksi sepatu premium dari berbagai brand ternama
                        dengan desain modern dan nyaman digunakan setiap hari.
                    </p>

                    <div class="flex flex-wrap gap-4 mt-8">

                        <button
                            class="bg-white text-black hover:bg-orange-500 hover:text-white px-8 py-3 rounded-xl text-[11px] uppercase tracking-[0.25em] font-black transition-all hover:scale-105">

                            BELI SEKARANG

                        </button>

                        <button
                            class="border border-white/20 text-white hover:bg-white hover:text-black px-8 py-3 rounded-xl text-[11px] uppercase tracking-[0.25em] font-black transition-all">

                            EXPLORE

                        </button>

                    </div>

                    {{-- STATS --}}
                    <div class="flex flex-wrap items-center gap-8 mt-10">

                        <div>
                            <h3 class="text-white text-3xl font-black">250+</h3>
                            <p class="text-gray-400 text-[10px] uppercase tracking-[0.3em] mt-1">
                                Products
                            </p>
                        </div>

                        <div>
                            <h3 class="text-white text-3xl font-black">50+</h3>
                            <p class="text-gray-400 text-[10px] uppercase tracking-[0.3em] mt-1">
                                Brands
                            </p>
                        </div>

                        <div>
                            <h3 class="text-white text-3xl font-black">10K+</h3>
                            <p class="text-gray-400 text-[10px] uppercase tracking-[0.3em] mt-1">
                                Customers
                            </p>
                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="relative flex justify-center items-center">

                    {{-- BACKGROUND GLOW --}}
                    <div
                        class="absolute w-[260px] h-[260px] md:w-[380px] md:h-[380px] bg-orange-500/10 blur-[90px] rounded-full">
                    </div>

                    {{-- SHOE --}}
                    <img src="{{ asset('img/sepatu.png') }}"
                        class="relative z-10 w-full max-w-sm md:max-w-xl object-contain">

                </div>

            </div>

        </div>

    </section>

    {{-- ================= BRANDS ================= --}}
    <section class="bg-white border-y border-gray-100">

        <div class="max-w-screen-2xl mx-auto px-6 md:px-12 py-10">

            {{-- TITLE --}}
            <div class="flex items-end justify-between mb-8">

                <div>

                    <span class="text-orange-500 text-xs uppercase tracking-[0.3em] font-black">
                        TOP BRANDS
                    </span>

                    <h2 class="text-3xl md:text-5xl font-black italic uppercase tracking-[-0.06em] mt-2">
                        Trusted Brands
                    </h2>

                </div>

                <a href="{{ route('brands.index') }}"
                    class="hidden md:flex text-sm font-black uppercase tracking-widest text-gray-400 hover:text-orange-500 transition-all">

                    View All

                </a>

            </div>

            {{-- BRAND GRID --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-5">

                @php
                    $brands = \App\Models\Brand::with('images')->orderBy('name')->take(5)->get();
                @endphp

                @forelse($brands as $brand)
                    <a href="{{ route('products.etalase') }}?brand={{ $brand->slug }}"
                        class="glass rounded-[2rem] py-7 px-6 flex flex-col items-center justify-center border border-gray-100 hover:shadow-2xl hover:border-orange-200 transition-all duration-500 group no-underline hover:no-underline">

                        {{-- LOGO --}}
                        @if ($brand->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $brand->images->first()->image_path) }}"
                                alt="{{ $brand->name }}"
                                class="h-10 md:h-12 object-contain opacity-70 group-hover:opacity-100 grayscale group-hover:grayscale-0 transition-all duration-500">
                        @else
                            <div
                                class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-xl font-black text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">

                                {{ substr($brand->name, 0, 1) }}

                            </div>
                        @endif

                        {{-- NAME --}}
                        <span
                            class="mt-4 font-black uppercase tracking-[0.2em] text-[11px] text-gray-700 group-hover:text-orange-500 transition-all text-center">

                            {{ $brand->name }}

                        </span>

                    </a>

                @empty

                    <div class="col-span-full text-center py-10">

                        <p class="text-gray-400 font-medium">
                            No Brands Available
                        </p>

                    </div>
                @endforelse

            </div>

        </div>

    </section>

    {{-- ================= CATEGORY ================= --}}
    <section class="max-w-screen-2xl mx-auto px-6 md:px-12 py-16">

        {{-- HEADER --}}
        <div class="flex items-end justify-between mb-10">

            <div>

                <span class="text-orange-500 text-xs uppercase tracking-[0.3em] font-black">
                    SPRINTZONE CATEGORY
                </span>

                <h2 class="text-4xl md:text-5xl font-black italic uppercase tracking-[-0.06em] mt-2">
                    Popular Category
                </h2>

                <p class="text-gray-500 mt-2">
                    Pilih kategori favoritmu
                </p>

            </div>

        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">

            @forelse($categories as $category)
                <a href="{{ route('products.etalase') }}?category={{ $category->slug }}"
                    class="relative overflow-hidden rounded-[2rem] min-h-[230px] group">

                    {{-- IMAGE --}}
                    @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-all duration-700">
                    @else
                        <div class="absolute inset-0 bg-gray-200"></div>
                    @endif

                    {{-- OVERLAY --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                    </div>

                    {{-- CONTENT --}}
                    <div class="relative z-10 h-full flex flex-col justify-end p-5">

                        <h3 class="text-white text-lg font-black italic uppercase tracking-wide">

                            {{ $category->name }}

                        </h3>

                        <p class="text-gray-300 text-xs mt-2 line-clamp-2">

                            {{ $category->description }}

                        </p>

                    </div>

                </a>

            @empty

                <div class="col-span-full text-center py-10">

                    <p class="text-gray-400">
                        No Categories Available
                    </p>

                </div>
            @endforelse

        </div>

    </section>

    {{-- ================= BANNERS ================= --}}
    <section class="max-w-screen-2xl mx-auto px-6 md:px-12 pb-16">

        <div class="grid md:grid-cols-3 gap-6">

            {{-- BIG --}}
            <div
                class="banner-card relative md:col-span-2 bg-black rounded-[2.5rem] overflow-hidden min-h-[420px] p-10 flex items-center">

                <div class="relative z-20 max-w-md">

                    <span class="text-orange-500 text-xs font-black tracking-[0.3em] uppercase">
                        LIMITED DEAL
                    </span>

                    <h2
                        class="text-white text-5xl md:text-7xl font-black italic uppercase leading-[0.9] tracking-[-0.08em] mt-4">
                        DISCOUNT <br>
                        UP TO 50%
                    </h2>

                    <p class="text-gray-400 mt-5 text-sm leading-relaxed">
                        Dapatkan penawaran terbaik untuk semua koleksi pilihan SprintZone.
                    </p>

                    <button
                        class="mt-8 bg-white text-black hover:bg-orange-500 hover:text-white px-8 py-4 rounded-xl text-xs uppercase tracking-[0.3em] font-black transition-all">
                        SHOP NOW
                    </button>

                </div>

                <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?q=80&w=2070"
                    class="absolute right-[-50px] bottom-[-20px] w-[420px] rotate-[-20deg]">

            </div>

            {{-- SMALL --}}
            <div class="flex flex-col gap-6">

                <div class="banner-card relative bg-white rounded-[2.5rem] p-8 overflow-hidden min-h-[200px]">

                    <span class="text-orange-500 text-xs uppercase tracking-[0.3em] font-black">
                        NEW SEASON
                    </span>

                    <h3 class="text-3xl font-black italic uppercase tracking-[-0.06em] mt-3">
                        SPRING <br>
                        SUMMER 2026
                    </h3>

                    <img src="https://images.unsplash.com/photo-1605348532760-6753d2c43329?q=80&w=2070"
                        class="absolute bottom-[-10px] right-[-20px] w-56 rotate-[-15deg]">

                </div>

                <div
                    class="banner-card relative bg-orange-500 rounded-[2.5rem] p-8 overflow-hidden min-h-[200px] text-white">

                    <span class="text-xs uppercase tracking-[0.3em] font-black">
                        EXTRA OFFER
                    </span>

                    <h3 class="text-5xl font-black italic tracking-[-0.08em] mt-3">
                        10% OFF
                    </h3>

                    <p class="mt-3 text-sm text-orange-100">
                        Gunakan kode :
                        <span class="font-black">SPRINT10</span>
                    </p>

                    <div
                        class="absolute right-[-20px] bottom-[-40px] text-[12rem] font-black text-white/10 italic leading-none">
                        %
                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- ================= PRODUCTS ================= --}}
    <section class="max-w-screen-2xl mx-auto px-6 md:px-12 pb-20">

        <div class="flex items-end justify-between mb-12">

            <div>

                <span class="text-orange-500 text-xs uppercase tracking-[0.3em] font-black">
                    SPRINTZONE COLLECTION
                </span>

                <h2 class="text-4xl md:text-6xl font-black italic uppercase tracking-[-0.08em] mt-3">
                    Best Seller
                </h2>

            </div>

        </div>

        {{-- PRODUCT GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">

            @forelse($products ?? [] as $product)
                <div class="product-card bg-white rounded-[2rem] overflow-hidden p-4 border border-gray-100">

                    <a href="{{ route('products.show', $product->id) }}" class="block no-underline hover:no-underline">

                        {{-- IMAGE --}}
                        <div
                            class="relative aspect-square overflow-hidden rounded-[1.5rem] bg-gradient-to-br from-gray-100 to-gray-200">

                            @php
                                $productImage = null;

                                if ($product->variants->isNotEmpty()) {
                                    $variantWithImage = $product->variants->firstWhere(fn($v) => !empty($v['image']));

                                    if ($variantWithImage) {
                                        $productImage = $variantWithImage['image'];
                                    }
                                }
                            @endphp

                            @if ($productImage)
                                <img src="{{ asset('storage/' . $productImage) }}"
                                    class="product-image w-full h-full object-cover">
                            @endif

                            {{-- STATUS --}}
                            <div class="absolute top-4 left-4 flex gap-2">

                                @if ($product->status == 'active')
                                    <span
                                        class="bg-black text-white text-[10px] font-black uppercase italic px-3 py-1 rounded-full">
                                        NEW
                                    </span>
                                @endif

                            </div>

                        </div>

                        {{-- INFO --}}
                        <div class="pt-5">

                            <div class="text-[10px] text-orange-500 font-black uppercase tracking-[0.3em]">
                                {{ $product->brand->name ?? 'No Brand' }}
                            </div>

                            <h3 class="font-bold text-sm md:text-base text-gray-900 mt-2 line-clamp-2">
                                {{ $product->name }}
                            </h3>

                            @php
                                $minPrice = 0;
                                $minDiscount = null;

                                if ($product->variants->isNotEmpty()) {
                                    $variantPrices = $product->variants->pluck('price')->filter();
                                    $variantDiscounts = $product->variants->pluck('discount_price')->filter();

                                    if ($variantPrices->isNotEmpty()) {
                                        $minPrice = $variantPrices->min();
                                    }

                                    if ($variantDiscounts->isNotEmpty()) {
                                        $minDiscount = $variantDiscounts->min();
                                    }
                                }

                                $hasDiscount = $minDiscount && $minDiscount < $minPrice;
                            @endphp

                            @if ($hasDiscount)
                                <div class="flex items-center gap-2 mt-3">

                                    <div class="text-2xl font-black italic tracking-[-0.06em]">
                                        Rp {{ number_format($minDiscount, 0, ',', '.') }}
                                    </div>

                                    <div class="text-xs line-through text-gray-400">
                                        Rp {{ number_format($minPrice, 0, ',', '.') }}
                                    </div>

                                </div>
                            @else
                                <div class="text-2xl font-black italic tracking-[-0.06em] mt-3">
                                    Rp {{ number_format($minPrice, 0, ',', '.') }}
                                </div>
                            @endif

                        </div>

                    </a>

                    {{-- BUTTON --}}
                    @auth

                        <button type="button"
                            onclick="handleAddToCart({{ $product->id }}, '{{ str_replace("'", "\\'", $product->name) }}')"
                            class="w-full mt-5 bg-gray-100 hover:bg-orange-500 hover:text-white transition-all py-3 rounded-2xl text-[11px] font-black uppercase tracking-[0.25em]">
                            ADD TO CART
                        </button>
                    @else
                        <a href="{{ route('login') }}"
                            class="block text-center w-full mt-5 bg-gray-100 hover:bg-orange-500 hover:text-white transition-all py-3 rounded-2xl text-[11px] font-black uppercase tracking-[0.25em]">
                            ADD TO CART
                        </a>

                    @endauth

                </div>

            @empty

                <div class="col-span-full py-24 text-center">

                    <div class="w-24 h-24 rounded-full bg-gray-100 mx-auto flex items-center justify-center text-4xl mb-6">
                        📦
                    </div>

                    <h3 class="text-3xl font-black italic uppercase">
                        Product Empty
                    </h3>

                    <p class="text-gray-500 mt-3">
                        Belum ada produk tersedia.
                    </p>

                </div>
            @endforelse

        </div>

    </section>

    {{-- ================= MODAL ================= --}}
    {{-- PAKAI MODAL KAMU YANG TADI --}}

@endsection

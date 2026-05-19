@extends('layouts.app')

@section('title', 'Brands')

@section('content')
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">

        @php
            $brands = \App\Models\Brand::with('images')->orderBy('name')->get();
        @endphp

        <div class="mb-4">
            <h2 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter">Our <span
                    class="text-orange-500">Brands</span></h2>
            <p class="text-gray-500 font-medium">Explore the best world-class footwear technology.</p>
        </div>

        @if ($brands->isEmpty())
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">Belum ada brand yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($brands as $brand)
                    <a href="{{ route('products.etalase') }}?brand={{ $brand->slug }}"
                        class="no-underline brand-card block bg-gray-50 rounded-3xl p-8 flex flex-col items-center justify-center border border-gray-100 hover:shadow-2xl hover:border-orange-200 hover:no-underline transition-all cursor-pointer group">

                        @if ($brand->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $brand->images->first()->image_path) }}"
                                class="h-20 w-auto mb-4 object-contain transition-transform duration-500 grayscale group-hover:grayscale-0"
                                alt="{{ $brand->name }}">
                        @else
                            <div class="h-20 w-auto mb-4 flex items-center justify-center">
                                <span class="text-4xl font-black text-gray-300">{{ substr($brand->name, 0, 1) }}</span>
                            </div>
                        @endif

                        <span
                            class="font-black italic uppercase tracking-widest text-sm text-center text-gray-800 group-hover:text-orange-600">
                            {{ $brand->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
@endsection

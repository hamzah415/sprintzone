@extends('layouts.app')

@section('content')
    //deskripsi produk
    <style>
        .description {
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .description h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            position: relative;
            padding-bottom: 12px;
        }

        .description h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #f59e0b, #d97706);
            border-radius: 2px;
        }

        .description p {
            font-size: 0.95rem;
            line-height: 1.7;
            color: #6b7280;
        }
    </style>
    <div class="min-h-screen bg-white">
        {{-- BREADCRUMB --}}
        <div class="max-w-5xl mx-auto px-6 py-6">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="/" class="hover:text-orange-600">Home</a>
                <span>/</span>
                <a href="{{ route('products.etalase') }}" class="hover:text-orange-600">Etalase</a>
                <span>/</span>
                <span class="text-gray-800 truncate">{{ $product->name }}</span>
            </nav>
        </div>

        {{-- PRODUCT DETAIL --}}
        <div class="max-w-5xl mx-auto px-6 pb-16">
            <div class="grid md:grid-cols-2 gap-12">

                {{-- LEFT IMAGE --}}
                <div class="space-y-4">
                    {{-- Main Image --}}
                    <div class="bg-gray-50 rounded-xl overflow-hidden relative group">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-auto object-cover" id="mainImage">
                        @else
                            <div class="w-full h-96 flex items-center justify-center text-gray-400">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif

                        {{-- Wishlist Button --}}
                        <form action="#" method="POST" class="absolute top-4 right-4">
                            @csrf
                            <button type="button"
                                class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>
                        </form>

                        <!-- Deskripsi -->
                        <div class="description">
                            <h3>Deskripsi Produk</h3>
                            <p>{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>

                    {{-- Thumbnail Gallery (Jika ada gambar tambahan) --}}
                    @if ($product->gallery_images)
                        <div class="flex gap-3 overflow-x-auto">
                            @foreach (json_decode($product->gallery_images) as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:ring-2 hover:ring-orange-500 transition"
                                    onclick="document.getElementById('mainImage').src = this.src">
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- RIGHT INFO --}}
                <div class="space-y-5">

                    {{-- BRAND & CATEGORY --}}
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">
                            {{ $product->brand->name ?? 'BRAND' }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="text-xs text-gray-500">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>

                    {{-- PRODUCT NAME --}}
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        {{ $product->name }}
                    </h1>

                    {{-- RATING & SOLD --}}
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= 4 ? 'text-orange-400' : 'text-gray-300' }}" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            @endfor
                            <span class="text-sm text-gray-500 ml-1">(127)</span>
                        </div>
                        <span class="text-gray-300">|</span>
                        <span class="text-sm text-gray-500">Terjual {{ $product->sold_count ?? 0 }}+</span>
                    </div>

                    {{-- PRICE --}}
                    <div>
                        @if ($product->discount_price)
                            <div class="flex items-baseline gap-3">
                                <span class="text-2xl font-bold text-gray-900">Rp
                                    {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                <span class="text-base text-gray-400 line-through">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <span class="text-2xl font-bold text-gray-900">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    {{-- STOCK WARNING --}}
                    <div class="flex items-center gap-2">
                        @if ($product->stock > 0 && $product->stock <= 5)
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded">
                                ⚠️ Stok hampir habis!
                            </span>
                        @endif
                        <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $product->stock > 0 ? '✓ Stok tersedia (' . $product->stock . ')' : '✗ Stok habis' }}
                        </span>
                    </div>

                    <!-- Warna -->
                    <div class="option-group">
                        <label>Warna:</label>
                        @if ($product->color)
                            <div class="color-options">
                                @foreach (explode(',', $product->color) as $color)
                                    <button type="button" class="color-btn" data-color="{{ trim($color) }}">
                                        <span style="background-color: {{ trim($color) }};"></span>
                                        {{ trim($color) }}
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <p class="no-options">Warna tidak tersedia</p>
                        @endif
                    </div>

                    <!-- Ukuran -->
                    <div class="option-group">
                        <label>Ukuran:</label>
                        @if ($product->size)
                            <div class="size-options">
                                @foreach (explode(',', $product->size) as $size)
                                    <button type="button" class="size-btn" data-size="{{ trim($size) }}">
                                        {{ trim($size) }}
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <p class="no-options">Ukuran tidak tersedia</p>
                        @endif
                    </div>

                    {{-- QUANTITY SELECTOR --}}
                    @auth
                        <div class="flex items-center gap-4 py-4 border-y border-gray-100">
                            <span class="text-sm text-gray-600">Jumlah:</span>
                            <div class="flex items-center border rounded-lg">
                                <button type="button" onclick="decreaseQty()"
                                    class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100">-</button>
                                <input type="number" name="qty" id="qtyInput" value="1" min="1"
                                    max="{{ $product->stock }}" class="w-12 text-center border-none outline-none" readonly>
                                <button type="button" onclick="increaseQty()"
                                    class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                        </div>
                    @endauth

                    {{-- BUTTON --}}
                    <div class="pt-2">
                        @auth
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex gap-3">
                                @csrf
                                <input type="hidden" name="qty" id="qtyHidden" value="1">
                                <button type="submit"
                                    class="flex-1 bg-black hover:bg-orange-600 text-white px-8 py-3 rounded-lg font-medium transition">
                                    + Add To Cart
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full text-center px-8 py-3 hover:no-underline bg-black hover:bg-orange-600 text-white rounded-lg font-medium transition">
                                + Add To Cart
                            </a>
                        @endauth
                    </div>

                    {{-- PRODUCT INFO --}}
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Berat</span>
                            <span class="font-medium">{{ $product->weight ?? 0 }} gram</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-medium">{{ $product->category->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500"> kondisi</span>
                            <span class="font-medium">{{ $product->condition == 'new' ? 'Baru' : 'new' }}</span>
                        </div>
                    </div>

                    {{-- BACK LINK --}}
                    <div class="pt-4">
                        <a href="{{ route('products.etalase') }}" class="text-orange-600 hover:underline text-sm">
                            ← Kembali ke Etalase
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT FOR QUANTITY --}}
    <script>
        function increaseQty() {
            let input = document.getElementById('qtyInput');
            let max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
                document.getElementById('qtyHidden').value = input.value;
            }
        }

        function decreaseQty() {
            let input = document.getElementById('qtyInput');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                document.getElementById('qtyHidden').value = input.value;
            }
        }
    </script>
@endsection

@extends('layouts.app')

@section('title', $product->name)

@section('content')

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

        .variant-btn.active {
            background: black;
            color: white;
            border-color: black;
        }

        .variant-btn.hidden {
            display: none;
        }
    </style>
    <div class="min-h-screen bg-white">
        {{-- BREADCRUMB --}}
        <div class="max-w-6xl mx-auto px-6 py-6">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="/" class="hover:text-orange-600">Home</a>
                <span>/</span>
                <a href="{{ route('products.etalase') }}" class="hover:text-orange-600">
                    Etalase
                </a>
                <span>/</span>
                <span class="text-gray-800 truncate">
                    {{ $product->name }}
                </span>
            </nav>
        </div>

        {{-- PRODUCT DETAIL --}}
        <div class="max-w-6xl mx-auto px-6 pb-16">
            <div class="grid md:grid-cols-2 gap-14">
                {{-- LEFT --}}
                <div class="space-y-5">
                    {{-- MAIN IMAGE --}}
                    <div class="bg-gray-100 rounded-2xl overflow-hidden relative">
                        @php
                            $mainImage = $product->image;
                            if (!$mainImage && $product->variants->isNotEmpty()) {
                                $variantWithImage = $product->variants->firstWhere(fn($v) => !empty($v['image']));
                                if ($variantWithImage) {
                                    $mainImage = $variantWithImage['image'];
                                }
                            }

                            $mainPrice = $product->price ?? 0;
                            $mainDiscount = $product->discount_price ?? null;
                            if (!$mainPrice && $product->variants->isNotEmpty()) {
                                $variantPrices = $product->variants->pluck('price')->filter();
                                $variantDiscounts = $product->variants->pluck('discount_price')->filter();
                                if ($variantPrices->isNotEmpty()) {
                                    $mainPrice = $variantPrices->min();
                                }
                                if ($variantDiscounts->isNotEmpty()) {
                                    $mainDiscount = $variantDiscounts->min();
                                }
                            }

                            $totalStock = $product->variants->sum('stock') ?? ($product->stock ?? 0);
                        @endphp

                        @if ($mainImage)
                            <img src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}" id="mainImage"
                                class="w-full h-auto object-cover">
                        @else
                            <div class="w-full h-[500px] flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif

                        {{-- LABEL HABIS KALO STOCK 0 --}}
                        @if ($totalStock <= 0)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center rounded-2xl">
                                <span class="bg-red-600 text-white text-lg font-black px-6 py-2 uppercase italic rounded">
                                    Habis
                                </span>
                            </div>
                        @endif

                    </div>

                    {{-- GALLERY (dari variant images) --}}
                    @php
                        // Kumpulin semua gambar dari variant
                        $allImages = collect();
                        if ($product->image) {
                            $allImages->push($product->image);
                        }
                        foreach ($product->variants as $v) {
                            if ($v->image) {
                                $allImages->push($v->image);
                            }
                        }
                        $allImages = $allImages->unique();
                    @endphp

                    @if ($allImages->count() > 1)
                        <div class="flex gap-3 overflow-x-auto">
                            @foreach ($allImages as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-24 h-24 rounded-xl object-cover cursor-pointer border hover:border-orange-500 transition"
                                    onclick="document.getElementById('mainImage').src = this.src">
                            @endforeach
                        </div>
                    @endif

                    {{-- DESCRIPTION --}}
                    <div class="description">
                        <h3>Deskripsi Produk</h3>
                        <p>
                            {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                        </p>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="space-y-6">

                    {{-- BRAND --}}
                    <div class="flex items-center gap-3">
                        <span class="uppercase text-xs font-bold tracking-wider text-orange-600">
                            {{ $product->brand->name ?? 'Brand' }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="text-sm text-gray-500">
                            {{ $product->category->name ?? 'Kategori' }}
                        </span>
                    </div>

                    {{-- PRODUCT NAME --}}
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight">
                        {{ $product->name }}
                    </h1>

                    {{-- RATING --}}
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
                            <span class="text-sm text-gray-500 ml-2">(127)</span>
                        </div>
                        <span class="text-gray-300">|</span>
                        <span class="text-sm text-gray-500">
                            Terjual {{ $product->sold_count ?? 0 }}+
                        </span>
                    </div>

                    {{-- PRICE (dari produk atau variant) --}}
                    @php
                        // Cek harga: produk dulu, kalo 0 ambil dari variant termurah
                        $mainPrice = $product->price ?? 0;
                        $mainDiscount = $product->discount_price ?? null;

                        if (!$mainPrice && $product->variants->isNotEmpty()) {
                            $variantPrices = $product->variants->pluck('price')->filter();
                            $variantDiscounts = $product->variants->pluck('discount_price')->filter();

                            if ($variantPrices->isNotEmpty()) {
                                $mainPrice = $variantPrices->min();
                            }
                            if ($variantDiscounts->isNotEmpty()) {
                                $mainDiscount = $variantDiscounts->min();
                            }
                        }

                        // Cek apakah ada discount (discount < price)
                        $hasDiscount = $mainDiscount && $mainDiscount < $mainPrice;
                    @endphp

                    <div>
                        <div class="flex items-center gap-4">
                            <span id="displayPrice" class="text-4xl font-bold text-gray-900">
                                @if ($hasDiscount)
                                    Rp {{ number_format($mainDiscount, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($mainPrice, 0, ',', '.') }}
                                @endif
                            </span>

                            {{-- HANYA TAMPILKAN KALO ADA DISCOUNT --}}
                            @if ($hasDiscount)
                                <span class="text-lg text-gray-400 line-through">
                                    Rp {{ number_format($mainPrice, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- STOCK --}}
                    @php
                        // Total stock dari semua variant
                        $totalStock = $product->variants->sum('stock') ?? $product->stock;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span id="stockText"
                            class="text-sm font-medium {{ $totalStock > 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $totalStock > 0 ? '✓ Stok tersedia (' . $totalStock . ')' : '✗ Stok habis' }}
                        </span>
                    </div>

                    {{-- VARIANT COLOR --}}
                    @if ($product->variants->count())
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">
                                Pilih Warna
                            </label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($product->variants->unique('color') as $variant)
                                    <button type="button"
                                        class="variant-btn color-btn border border-gray-300 px-4 py-2 rounded-xl text-sm font-medium transition"
                                        data-color="{{ $variant->color }}">
                                        {{ $variant->color }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- VARIANT SIZE --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">
                                Pilih Ukuran
                            </label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($product->variants->unique('size') as $variant)
                                    <button type="button"
                                        class="variant-btn size-btn border border-gray-300 px-4 py-2 rounded-xl text-sm font-medium transition"
                                        data-size="{{ $variant->size }}">
                                        {{ $variant->size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- VARIANT INFO --}}
                        <div id="variantInfo" class="hidden bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">SKU</span>
                                <span class="font-semibold" id="variantSku">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Harga</span>
                                <span class="font-semibold text-orange-600" id="variantPrice">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Stock</span>
                                <span class="font-semibold" id="variantStock">-</span>
                            </div>
                        </div>
                    @endif

                    {{-- QTY --}}
                    @auth
                        <div class="flex items-center gap-4 py-5 border-y">
                            <span class="text-sm text-gray-700">Jumlah</span>
                            <div class="flex items-center border rounded-xl overflow-hidden">
                                <button type="button" onclick="decreaseQty()" class="w-12 h-12 hover:bg-gray-100">-</button>
                                <input type="number" id="qtyInput" value="1" min="1" max="{{ $totalStock }}"
                                    readonly class="w-14 text-center border-0 outline-none">
                                <button type="button" onclick="increaseQty()" class="w-12 h-12 hover:bg-gray-100">+</button>
                            </div>
                        </div>
                    @endauth

                    {{-- BUTTON --}}
                    <div>
                        @auth
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" id="cartForm">
                                @csrf
                                <input type="hidden" name="qty" id="qtyHidden" value="1">
                                <input type="hidden" name="variant_id" id="variantId">

                                @if ($totalStock > 0)
                                    <button type="submit" id="addToCartBtn"
                                        class="w-full bg-black hover:bg-orange-600 text-white py-4 rounded-2xl font-semibold transition">
                                        + Add To Cart
                                    </button>
                                @else
                                    <button type="button" disabled
                                        class="w-full bg-gray-300 text-gray-500 py-4 rounded-2xl font-semibold cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @endif
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full text-center bg-black hover:bg-orange-600 text-white py-4 rounded-2xl font-semibold hover:no-underline">
                                + Add To Cart
                            </a>
                        @endauth
                    </div>

                    {{-- BACK --}}
                    <div>
                        <a href="{{ route('products.etalase') }}" class="text-orange-600 hover:underline text-sm">
                            ← Kembali ke Etalase
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        const variants = @json($product->variants);

        let selectedColor = null;
        let selectedSize = null;

        const colorButtons = document.querySelectorAll('.color-btn');
        const sizeButtons = document.querySelectorAll('.size-btn');

        // Get all unique colors and sizes
        const allColors = [...new Set(variants.map(v => v.color))];
        const allSizes = [...new Set(variants.map(v => v.size))];

        // Inisialisasi: tampilkan semua
        renderSizeButtons(allSizes);

        colorButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Reset semua color button
                colorButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                selectedColor = this.dataset.color;

                // Filter size berdasarkan color yang dipilih
                const availableSizes = variants
                    .filter(v => v.color === selectedColor)
                    .map(v => v.size);

                renderSizeButtons([...new Set(availableSizes)]);

                // Reset size jika tidak tersedia
                const sizeBtnEl = document.querySelector(`.size-btn[data-size="${selectedSize}"]`);
                if (!sizeBtnEl || !availableSizes.includes(selectedSize)) {
                    selectedSize = null;
                    hideVariantInfo();
                }

                findVariant();
            });
        });

        sizeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Reset semua size button
                sizeButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                selectedSize = this.dataset.size;

                // Filter color berdasarkan size yang dipilih
                const availableColors = variants
                    .filter(v => v.size === selectedSize)
                    .map(v => v.color);

                renderColorButtons([...new Set(availableColors)]);

                // Reset color jika tidak tersedia
                const colorBtnEl = document.querySelector(`.color-btn[data-color="${selectedColor}"]`);
                if (!colorBtnEl || !availableColors.includes(selectedColor)) {
                    selectedColor = null;
                    hideVariantInfo();
                }

                findVariant();
            });
        });

        function renderSizeButtons(sizes) {
            sizeButtons.forEach(btn => {
                if (sizes.includes(btn.dataset.size)) {
                    btn.classList.remove('hidden');
                } else {
                    btn.classList.add('hidden');
                    btn.classList.remove('active');
                }
            });
        }

        function renderColorButtons(colors) {
            colorButtons.forEach(btn => {
                if (colors.includes(btn.dataset.color)) {
                    btn.classList.remove('hidden');
                } else {
                    btn.classList.add('hidden');
                    btn.classList.remove('active');
                }
            });
        }

        function hideVariantInfo() {
            document.getElementById('variantInfo').classList.add('hidden');
            document.getElementById('variantId').value = '';
        }

        function findVariant() {
            // Jika belum memilih color dan size, abort
            if (!selectedColor || !selectedSize) {
                hideVariantInfo();
                return;
            }

            const variant = variants.find(v =>
                v.color === selectedColor &&
                v.size === selectedSize
            );

            if (variant) {
                const hasDiscount = variant.discount_price && variant.discount_price < variant.price;

                document.getElementById('variantInfo').classList.remove('hidden');
                document.getElementById('variantSku').innerText = variant.sku ?? '-';

                if (hasDiscount) {
                    document.getElementById('variantPrice').innerText = 'Rp ' + Number(variant.discount_price)
                        .toLocaleString('id-ID');
                    document.getElementById('displayPrice').innerText = 'Rp ' + Number(variant.discount_price)
                        .toLocaleString('id-ID');
                } else {
                    document.getElementById('variantPrice').innerText = 'Rp ' + Number(variant.price).toLocaleString(
                        'id-ID');
                    document.getElementById('displayPrice').innerText = 'Rp ' + Number(variant.price).toLocaleString(
                        'id-ID');
                }

                document.getElementById('variantStock').innerText = variant.stock;
                document.getElementById('stockText').innerText = '✓ Stok tersedia (' + variant.stock + ')';
                document.getElementById('variantId').value = variant.id;
                document.getElementById('qtyInput').max = variant.stock;
                document.getElementById('qtyInput').value = 1;
                document.getElementById('qtyHidden').value = 1;
            }
        }

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

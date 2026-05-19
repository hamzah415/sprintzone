@extends('layouts.app')

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

                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" id="mainImage"
                                class="w-full h-auto object-cover">
                        @else
                            <div class="w-full h-[500px] flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif

                    </div>

                    {{-- GALLERY --}}
                    @if ($product->gallery_images)
                        <div class="flex gap-3 overflow-x-auto">

                            @foreach (json_decode($product->gallery_images) as $img)
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

                    {{-- PRICE --}}
                    <div>

                        <div class="flex items-center gap-4">

                            <span id="displayPrice" class="text-4xl font-bold text-gray-900">

                                Rp {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}

                            </span>

                            @if ($product->discount_price)
                                <span class="text-lg text-gray-400 line-through">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            @endif

                        </div>

                    </div>

                    {{-- STOCK --}}
                    <div class="flex items-center gap-3">

                        <span id="stockText"
                            class="text-sm font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">

                            {{ $product->stock > 0 ? '✓ Stok tersedia (' . $product->stock . ')' : '✗ Stok habis' }}

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

                            <span class="text-sm text-gray-700">
                                Jumlah
                            </span>

                            <div class="flex items-center border rounded-xl overflow-hidden">

                                <button type="button" onclick="decreaseQty()" class="w-12 h-12 hover:bg-gray-100">
                                    -
                                </button>

                                <input type="number" id="qtyInput" value="1" min="1" max="{{ $product->stock }}"
                                    readonly class="w-14 text-center border-0 outline-none">

                                <button type="button" onclick="increaseQty()" class="w-12 h-12 hover:bg-gray-100">
                                    +
                                </button>

                            </div>

                        </div>

                    @endauth

                    {{-- BUTTON --}}
                    <div>

                        @auth

                            <form action="{{ route('cart.add', $product->id) }}" method="POST">

                                @csrf

                                <input type="hidden" name="qty" id="qtyHidden" value="1">

                                <input type="hidden" name="variant_id" id="variantId">

                                <button type="submit"
                                    class="w-full bg-black hover:bg-orange-600 text-white py-4 rounded-2xl font-semibold transition">

                                    + Add To Cart

                                </button>

                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full text-center bg-black hover:bg-orange-600 text-white py-4 rounded-2xl font-semibold hover:no-underline">

                                + Add To Cart

                            </a>

                        @endauth

                    </div>

                    {{-- PRODUCT INFO --}}
                    <div class="bg-gray-50 rounded-2xl p-5 space-y-3 text-sm">

                        <div class="flex justify-between">
                            <span class="text-gray-500">Berat</span>
                            <span class="font-medium">
                                {{ $product->weight ?? 0 }} gram
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-medium">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Kondisi</span>
                            <span class="font-medium">
                                {{ $product->condition == 'new' ? 'Baru' : 'Bekas' }}
                            </span>
                        </div>

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

        colorButtons.forEach(btn => {

            btn.addEventListener('click', function() {

                colorButtons.forEach(b => b.classList.remove('active'));

                this.classList.add('active');

                selectedColor = this.dataset.color;

                findVariant();

            });

        });

        sizeButtons.forEach(btn => {

            btn.addEventListener('click', function() {

                sizeButtons.forEach(b => b.classList.remove('active'));

                this.classList.add('active');

                selectedSize = this.dataset.size;

                findVariant();

            });

        });

        function findVariant() {

            const variant = variants.find(v =>
                v.color == selectedColor &&
                v.size == selectedSize
            );

            if (variant) {

                document.getElementById('variantInfo')
                    .classList.remove('hidden');

                document.getElementById('variantSku')
                    .innerText = variant.sku ?? '-';

                document.getElementById('variantPrice')
                    .innerText =
                    'Rp ' + Number(
                        variant.discount_price ?? variant.price
                    ).toLocaleString('id-ID');

                document.getElementById('variantStock')
                    .innerText = variant.stock;

                document.getElementById('displayPrice')
                    .innerText =
                    'Rp ' + Number(
                        variant.discount_price ?? variant.price
                    ).toLocaleString('id-ID');

                document.getElementById('stockText')
                    .innerText =
                    '✓ Stok tersedia (' + variant.stock + ')';

                document.getElementById('variantId')
                    .value = variant.id;

                document.getElementById('qtyInput')
                    .max = variant.stock;
            }

        }

        function increaseQty() {

            let input = document.getElementById('qtyInput');

            let max = parseInt(input.max);

            if (parseInt(input.value) < max) {

                input.value = parseInt(input.value) + 1;

                document.getElementById('qtyHidden').value =
                    input.value;

            }

        }

        function decreaseQty() {

            let input = document.getElementById('qtyInput');

            if (parseInt(input.value) > 1) {

                input.value = parseInt(input.value) - 1;

                document.getElementById('qtyHidden').value =
                    input.value;

            }

        }
    </script>

@endsection

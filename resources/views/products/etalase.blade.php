@extends('layouts.app')

@section('title', 'Etalase Produk')

@section('content')
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="text-3xl md:text-4xl font-black italic uppercase tracking-tighter">Most <span
                        class="text-orange-500">Wanted</span></h2>
                <p class="text-gray-500 font-medium">No limits. Just your best performance ever.</p>
            </div>
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
        @if (request()->has('brand'))
            @php
                $currentBrand = \App\Models\Brand::where('slug', request('brand'))->first();
            @endphp
            @if ($currentBrand)
                <div class="mb-6 flex items-center gap-2">
                    <span class="text-sm text-gray-500">Filter:</span>
                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $currentBrand->name }}
                        <a href="{{ route('products.etalase', request()->except('brand')) }}"
                            class="ml-2 text-orange-500 hover:text-orange-700">×</a>
                    </span>
                </div>
            @endif
        @endif

        {{-- PRODUCT GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            @forelse($products ?? [] as $product)
                <div class="group product-item" data-product-id="{{ $product->id }}">

                    <a href="{{ route('products.show', $product->id) }}" class="block no-underline hover:no-underline">
                        {{-- IMAGE (dari variant) --}}
                        <div class="relative aspect-square overflow-hidden rounded-2xl bg-gray-100 mb-4">
                            @php
                                // Cari gambar dari variant
                                $productImage = null;
                                if ($product->variants->isNotEmpty()) {
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

                            {{-- STATUS LABEL --}}
                            <div class="absolute top-3 left-3">
                                @if ($product->status == 'active')
                                    <span
                                        class="bg-black text-white text-[9px] font-black px-2 py-1 uppercase italic rounded">New</span>
                                @endif
                            </div>

                            {{-- HABIS LABEL --}}
                            @php
                                // Cek total stock
                                $totalStock = 0;
                                if ($product->variants->isNotEmpty()) {
                                    $totalStock = $product->variants->sum('stock');
                                } else {
                                    $totalStock = $product->stock ?? 0;
                                }
                            @endphp
                            @if ($totalStock <= 0)
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center rounded-2xl">
                                    <span
                                        class="bg-red-600 text-white text-xs font-black px-3 py-1 uppercase italic rounded">Habis</span>
                                </div>
                            @endif
                        </div>

                        {{-- INFO (harga dari variant) --}}
                        <div class="space-y-1">
                            <div class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">
                                {{ $product->brand->name ?? 'No Brand' }}
                            </div>
                            <h3 class="font-bold text-sm text-gray-800 leading-tight line-clamp-2">
                                {{ $product->name }}
                            </h3>
                            @php
                                // Cari harga dari variant termurah
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

                    {{-- BUTTON (disabled kalau habis) --}}
                    @auth
                        @if ($totalStock > 0)
                            <button type="button"
                                onclick="handleAddToCart({{ $product->id }}, '{{ str_replace("'", "\\'", $product->name) }}')"
                                class="w-full mt-4 bg-gray-100 group-hover:bg-orange-600 group-hover:text-white transition-colors py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                Add to Cart
                            </button>
                        @else
                            <button disabled
                                class="w-full mt-4 bg-gray-200 text-gray-500 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-not-allowed">
                                Habis
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="block w-full mt-4 text-center hover:no-underline bg-gray-100 hover:bg-orange-600 hover:text-white transition-colors py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                            Add to Cart
                        </a>
                    @endauth
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-400 font-medium text-sm tracking-wide">There is no product data yet.</p>
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

    {{-- VARIANT MODAL --}}
    <div id="variantModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl">
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="text-white font-black italic uppercase">Pilih Variant</h3>
                    <p class="text-gray-400 text-xs" id="modalProductName"></p>
                </div>
                <button onclick="closeVariantModal()"
                    class="text-gray-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>
            <form id="variantForm" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="variant_id" id="selectedVariantId">
                <input type="hidden" name="qty" id="variantQty" value="1">

                <div class="mb-4">
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Pilih Warna</label>
                    <div class="flex flex-wrap gap-2" id="colorOptions"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Pilih
                        Ukuran</label>
                    <div class="flex flex-wrap gap-2" id="sizeOptions"></div>
                </div>

                <div id="variantInfo" class="hidden bg-gray-50 rounded-xl p-4 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Stock:</span>
                        <span class="font-bold" id="modalStock">-</span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-500">Harga:</span>
                        <span class="font-bold text-orange-600" id="modalPrice">-</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 mb-4">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Jumlah</label>
                    <div class="flex items-center border rounded-lg overflow-hidden">
                        <button type="button" onclick="modalDecreaseQty()"
                            class="w-10 h-10 hover:bg-gray-100">-</button>
                        <input type="number" id="modalQtyInput" value="1" readonly
                            class="w-12 text-center border-0 outline-none">
                        <button type="button" onclick="modalIncreaseQty()"
                            class="w-10 h-10 hover:bg-gray-100">+</button>
                    </div>
                </div>

                <button type="submit" id="addToCartBtn" disabled
                    class="w-full bg-orange-600 text-white py-3 rounded-xl font-black uppercase disabled:opacity-50 disabled:cursor-not-allowed">
                    + Add To Cart
                </button>
            </form>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        const allProductVariants = {};

        @foreach ($products as $product)
            allProductVariants[{{ $product->id }}] = @json($product->variants);
        @endforeach

        let currentVariants = [];
        let selectedColor = null;
        let selectedSize = null;
        let selectedVariant = null;
        let currentProductId = null;

        function handleAddToCart(productId, productName) {
            currentProductId = productId;
            currentVariants = allProductVariants[productId] || [];

            if (currentVariants.length === 0) {
                directAddToCart(productId);
                return;
            }
            openVariantModal(productName);
        }

        function openVariantModal(productName) {
            document.getElementById('modalProductName').innerText = productName;
            document.getElementById('variantForm').action = '/cart/' + currentProductId;

            selectedColor = null;
            selectedSize = null;
            selectedVariant = null;

            const uniqueColors = [...new Set(currentVariants.map(v => v.color))];
            document.getElementById('colorOptions').innerHTML = uniqueColors.map(color =>
                `<button type="button" class="variant-btn border px-3 py-1 rounded text-xs font-medium" data-color="${color}">${color}</button>`
            ).join('');

            const uniqueSizes = [...new Set(currentVariants.map(v => v.size))];
            document.getElementById('sizeOptions').innerHTML = uniqueSizes.map(size =>
                `<button type="button" class="variant-btn border px-3 py-1 rounded text-xs font-medium" data-size="${size}">${size}</button>`
            ).join('');

            document.getElementById('variantInfo').classList.add('hidden');
            document.getElementById('addToCartBtn').disabled = true;
            document.getElementById('modalQtyInput').value = 1;
            document.getElementById('variantModal').classList.remove('hidden');
            document.getElementById('variantModal').classList.add('flex');

            document.querySelectorAll('#colorOptions .variant-btn').forEach(btn => {
                btn.onclick = function() {
                    document.querySelectorAll('#colorOptions .variant-btn').forEach(b => b.classList.remove(
                        'active', 'bg-black', 'text-white'));
                    this.classList.add('active', 'bg-black', 'text-white');
                    selectedColor = this.dataset.color;
                    findModalVariant();
                };
            });

            document.querySelectorAll('#sizeOptions .variant-btn').forEach(btn => {
                btn.onclick = function() {
                    document.querySelectorAll('#sizeOptions .variant-btn').forEach(b => b.classList.remove(
                        'active', 'bg-black', 'text-white'));
                    this.classList.add('active', 'bg-black', 'text-white');
                    selectedSize = this.dataset.size;
                    findModalVariant();
                };
            });
        }

        function directAddToCart(productId) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/cart/' + productId;
            form.style.display = 'none';

            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';

            form.appendChild(token);
            document.body.appendChild(form);
            form.submit();
        }

        function findModalVariant() {
            if (!selectedColor || !selectedSize) {
                selectedVariant = null;
                document.getElementById('variantInfo').classList.add('hidden');
                document.getElementById('addToCartBtn').disabled = true;
                document.getElementById('selectedVariantId').value = '';
                return;
            }

            selectedVariant = currentVariants.find(v => v.color === selectedColor && v.size === selectedSize);

            if (selectedVariant && selectedVariant.stock > 0) {
                const hasDiscount = selectedVariant.discount_price && selectedVariant.discount_price < selectedVariant
                    .price;
                const price = hasDiscount ? selectedVariant.discount_price : selectedVariant.price;

                document.getElementById('variantInfo').classList.remove('hidden');
                document.getElementById('modalStock').innerText = selectedVariant.stock;
                document.getElementById('modalPrice').innerText = 'Rp ' + Number(price).toLocaleString('id-ID');
                document.getElementById('selectedVariantId').value = selectedVariant.id;
                document.getElementById('modalQtyInput').max = selectedVariant.stock;
                document.getElementById('addToCartBtn').disabled = false;
            } else {
                document.getElementById('variantInfo').classList.add('hidden');
                document.getElementById('addToCartBtn').disabled = true;
            }
        }

        function closeVariantModal() {
            document.getElementById('variantModal').classList.add('hidden');
            document.getElementById('variantModal').classList.remove('flex');
        }

        function modalIncreaseQty() {
            const input = document.getElementById('modalQtyInput');
            const max = parseInt(input.max) || 1;
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function modalDecreaseQty() {
            const input = document.getElementById('modalQtyInput');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeVariantModal();
        });
    </script>
@endsection

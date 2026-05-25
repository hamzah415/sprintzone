@extends('layouts.admin.app')

@section('content')
    <style>
        /* Kostumisasi agar senada dengan SprintZone */
        .btn-sprint {
            background-color: #FF4500;
            color: white;
            border: none;
            font-weight: bold;
        }

        .btn-sprint:hover {
            background-color: #e63e00;
            color: white;
        }

        .card-sprint {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            border-top: none;
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>

    <div class="container-fluid py-4">
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl md:text-3xl font-black italic uppercase tracking-tight">
                PRODUCT <span class="text-orange-500">MANAGEMENT</span>
            </h2>
            <button class="btn btn-sprint px-4 shadow-sm" onclick="openProductModal('create')">
                <i class="fas fa-plus-circle me-2"></i> NEW PRODUCT
            </button>
        </div>


        {{-- TABLE --}}
        <div class="card card-sprint">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Act</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Product</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Category
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Brand</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Stock</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Created</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Updated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    {{-- ACTION --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button onclick="toggleVariant({{ $product->id }})"
                                                class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                                                <svg id="arrow-{{ $product->id }}" class="w-4 h-4 transition"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <button onclick='openProductModal("edit", @json($product))'
                                                class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button
                                                onclick="openDeleteModal('{{ route('products.destroy', $product->id) }}')"
                                                class="w-9 h-9 flex items-center justify-center rounded-full border border-red-200 text-red-500 hover:bg-red-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>

                                    {{-- PRODUCT --}}
                                    <td class="px-6 py-4">
                                        <div>
                                            <span
                                                class="font-bold text-gray-900 text-sm block uppercase">{{ $product->name }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $product->variants->count() }}
                                                variant</span>
                                        </div>
                                    </td>

                                    {{-- CATEGORY --}}
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 text-[10px] font-black uppercase rounded bg-gray-800 text-white tracking-tighter">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- BRAND --}}
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                        {{ $product->brand->name ?? '-' }}
                                    </td>

                                    {{-- STOCK --}}
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-green-600 font-bold text-xs">{{ $product->variants->sum('stock') }}
                                            pcs</span>
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">
                                        @if ($product->status == 'active')
                                            <span
                                                class="px-3 py-1 text-[10px] font-black uppercase rounded bg-green-600 text-white tracking-tighter">Active</span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-[10px] font-black uppercase rounded bg-gray-500 text-white tracking-tighter">Inactive</span>
                                        @endif
                                    </td>

                                    {{-- CREATED --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-gray-700">{{ $product->creator->name ?? '-' }}</span>
                                            <span
                                                class="text-xs text-gray-400">{{ $product->created_at->format('d M Y') }}</span>
                                        </div>
                                    </td>

                                    {{-- UPDATED --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-gray-700">{{ $product->updater->name ?? '-' }}</span>
                                            <span
                                                class="text-xs text-gray-400">{{ $product->updated_at ? $product->updated_at->format('d M Y') : '-' }}</span>
                                        </div>
                                    </td>
                                </tr>

                                {{-- VARIANT ROW --}}
                                <tr id="variant-row-{{ $product->id }}" class="hidden bg-gray-50">
                                    <td colspan="10" class="p-4">
                                        <div class="bg-white rounded border p-4">
                                            <div class="flex justify-between items-center mb-3">
                                                <div>
                                                    <h4 class="font-bold uppercase text-xs text-gray-700">Product Variants
                                                    </h4>
                                                    <p class="text-[10px] text-gray-400">Manage variations for this product
                                                    </p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    {{-- SELECT ALL CHECKBOX --}}
                                                    <label
                                                        class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer">
                                                        <input type="checkbox"
                                                            onchange="toggleAllVariants(this, {{ $product->id }})"
                                                            class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                                        <span class="font-medium">Pilih Semua</span>
                                                    </label>
                                                    <button onclick="openVariantModal({{ $product->id }})"
                                                        class="btn btn-sprint px-3 py-1 text-xs">+ Add</button>
                                                    <button type="button"
                                                        onclick="deleteSelectedVariants({{ $product->id }})"
                                                        class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700 hidden"
                                                        id="deleteSelectedBtn-{{ $product->id }}">
                                                        <i class="fas fa-trash-alt mr-1"></i> Hapus Terpilih
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($product->variants->count() > 0)
                                                <table class="w-full text-xs">
                                                    <thead>
                                                        <tr class="border-b text-gray-400">
                                                            <th class="py-2 text-left w-8">
                                                                <input type="checkbox" class="variant-checkbox hidden"
                                                                    disabled>
                                                            </th>
                                                            <th class="py-2 text-left">Act</th>
                                                            <th class="py-2 text-left">Img</th>
                                                            <th class="py-2 text-left">Color</th>
                                                            <th class="py-2 text-left">Size</th>
                                                            <th class="py-2 text-left">SKU</th>
                                                            <th class="py-2 text-left">Price</th>
                                                            <th class="py-2 text-left">Disc</th>
                                                            <th class="py-2 text-left">Stock</th>
                                                            <th class="py-2 text-left">Created</th>
                                                            <th class="py-2 text-left">Updated</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($product->variants as $variant)
                                                            <tr class="border-b variant-row-{{ $product->id }}">
                                                                <td class="py-2 w-8">
                                                                    <input type="checkbox" name="variant_ids[]"
                                                                        value="{{ $variant->id }}"
                                                                        class="variant-checkbox w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                                                        onchange="updateDeleteButton({{ $product->id }})">
                                                                </td>
                                                                <td class="py-2">
                                                                    <div class="flex items-center gap-1">
                                                                        <button
                                                                            onclick='openVariantEditModal(@json($variant))'
                                                                            class="w-7 h-7 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100">
                                                                            <svg class="w-3 h-3" fill="none"
                                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                            </svg>
                                                                        </button>
                                                                        <button
                                                                            onclick="openDeleteModal('{{ route('variants.destroy', $variant->id) }}')"
                                                                            class="w-7 h-7 flex items-center justify-center rounded-full border border-red-200 text-red-500 hover:bg-red-50">
                                                                            <svg class="w-3 h-3" fill="none"
                                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                                <td class="py-2">
                                                                    @if ($variant->image)
                                                                        <img src="{{ asset('storage/' . $variant->image) }}"
                                                                            class="w-8 h-8 rounded object-cover">
                                                                    @else
                                                                        <div
                                                                            class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center text-[6px]">
                                                                            No</div>
                                                                    @endif
                                                                </td>
                                                                <td class="py-2 font-bold">{{ $variant->color }}</td>
                                                                <td class="py-2">{{ $variant->size }}</td>
                                                                <td class="py-2">{{ $variant->sku ?? '-' }}</td>
                                                                <td class="py-2 font-bold">Rp
                                                                    {{ number_format($variant->price, 0, ',', '.') }}</td>
                                                                <td class="py-2 text-red-500">
                                                                    {{ $variant->discount_price ? 'Rp ' . number_format($variant->discount_price, 0, ',', '.') : '-' }}
                                                                </td>
                                                                <td class="py-2 font-bold text-green-600">
                                                                    {{ $variant->stock }}</td>
                                                                <td class="py-2">
                                                                    <span
                                                                        class="text-gray-500">{{ $variant->creator->name ?? '-' }}</span>
                                                                    <span
                                                                        class="text-gray-400 text-[10px] block">{{ $variant->created_at->format('d M Y') }}</span>
                                                                </td>
                                                                <td class="py-2">
                                                                    <span
                                                                        class="text-gray-500">{{ $variant->updater->name ?? '-' }}</span>
                                                                    <span
                                                                        class="text-gray-400 text-[10px] block">{{ $variant->updated_at ? $variant->updated_at->format('d M Y') : '-' }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="text-center text-gray-400 py-4 text-xs">No variant data</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-box text-gray-300 text-5xl mb-4"></i>
                                            <p class="text-gray-400 font-medium text-sm tracking-wide">There is no product
                                                data yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- PRODUCT MODAL --}}
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-black italic uppercase tracking-widest text-lg" id="modalTitle">Add New Product
                </h3>
                <button onclick="closeProductModal()"
                    class="text-gray-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>
            <form id="productForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
                class="p-6">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Product
                        Name</label>
                    <input type="text" name="name" id="productName"
                        class="w-full border-gray-200 border-2 text-sm font-bold p-3" required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Category</label>
                        <select name="category_id" id="productCategory"
                            class="w-full border-gray-200 border-2 text-sm font-bold p-3">
                            <option value="">Select</option>
                            @foreach (\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Brand</label>
                        <select name="brand_id" id="productBrand"
                            class="w-full border-gray-200 border-2 text-sm font-bold p-3">
                            <option value="">Select</option>
                            @foreach (\App\Models\Brand::all() as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-5">
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Status</label>
                    <select name="status" id="productStatus"
                        class="w-full border-gray-200 border-2 text-sm font-bold p-3">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Description</label>
                    <textarea name="description" id="productDesc" rows="3"
                        class="w-full border-gray-200 border-2 text-sm font-medium p-3"></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeProductModal()"
                        class="px-6 py-2 text-xs font-black uppercase tracking-widest text-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-8 py-2 bg-orange-600 text-white text-xs font-black uppercase tracking-widest hover:bg-orange-700 shadow-md">Save
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- VARIANT MODAL (SHOES STYLE) --}}
    <div id="variantModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden relative">

            {{-- HEADER --}}
            <div class="bg-gray-900 px-6 py-5 flex justify-between items-center border-b border-gray-800">
                <div>
                    <h3 class="text-white font-black italic uppercase tracking-widest text-xl" id="variantModalTitle">
                        Add Variant
                    </h3>
                    <p class="text-gray-400 text-xs mt-1">1 warna bisa banyak size</p>
                </div>
                <button onclick="closeVariantModal()"
                    class="text-gray-400 hover:text-white text-3xl font-bold leading-none">
                    &times;
                </button>
            </div>

            <form id="variantForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">
                <input type="hidden" name="product_id" id="variant_product_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- KIRI: WARNA & GAMBAR --}}
                    <div>
                        {{-- COLOR --}}
                        <div class="mb-0">
                            <label
                                class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Warna</label>
                            <div class="flex gap-2">
                                <input type="text" name="color" id="variant_color" placeholder="contoh: Hitam"
                                    class="flex-1 border-2 border-gray-200 rounded-lg text-sm font-bold p-3 focus:border-orange-500 outline-none"
                                    required>
                                <input type="color" id="colorPicker"
                                    class="w-14 h-12 border-2 border-gray-200 rounded-lg cursor-pointer bg-white"
                                    value="#000000"
                                    onchange="document.getElementById('variant_color').value = this.value">
                            </div>
                        </div>

                        {{-- IMAGE --}}
                        <div class="mt-4">
                            <label
                                class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Gambar</label>
                            <input type="file" name="image"
                                class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                        </div>

                        {{-- SKU --}}
                        <div class="mt-4">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">SKU
                                (Opsional)</label>
                            <input type="text" name="sku" id="variant_sku" placeholder="Auto-generate"
                                class="w-full border-2 border-gray-200 rounded-lg text-sm p-3 focus:border-orange-500 outline-none">
                        </div>
                    </div>

                    {{-- KANAN: UKURAN & HARGA --}}
                    <div>
                        {{-- SIZE BUTTONS (HORIZONTAL) --}}
                        <div class="mb-4">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Pilih
                                Sizes</label>
                            <div class="flex gap-2 overflow-x-auto pb-2 mb-2" id="sizeContainer">
                                @foreach (['36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46'] as $size)
                                    <button type="button" onclick="toggleSize('{{ $size }}')"
                                        data-size="{{ $size }}"
                                        class="size-btn flex-shrink-0 w-12 h-10 border-2 border-gray-200 rounded-lg text-xs font-bold transition hover:border-orange-500">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="sizes" id="variant_sizes" value="">
                            <input type="text" id="variant_sizes_manual" placeholder="Atau ketik ukuran lain: 47, 48"
                                class="w-full border-2 border-gray-200 rounded-lg text-sm p-2 focus:border-orange-500 outline-none"
                                oninput="document.getElementById('variant_sizes').value = this.value">
                        </div>

                        {{-- PRICE & DISCOUNT --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label
                                    class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Harga</label>
                                <input type="number" name="price" id="variant_price" placeholder="150000"
                                    class="w-full border-2 border-gray-200 rounded-lg text-sm p-3 focus:border-orange-500 outline-none"
                                    required>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Diskon</label>
                                <input type="number" name="discount_price" id="variant_discount_price"
                                    placeholder="Opsional"
                                    class="w-full border-2 border-gray-200 rounded-lg text-sm p-3 focus:border-orange-500 outline-none">
                            </div>
                        </div>

                        {{-- STOCK --}}
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Stock per
                                Size</label>
                            <input type="number" name="stock" id="variant_stock" value="10"
                                class="w-full border-2 border-gray-200 rounded-lg text-sm p-3 focus:border-orange-500 outline-none">
                        </div>
                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-between gap-3 pt-6 mt-2 border-t border-gray-100">
                    <button type="button" onclick="closeVariantModal()"
                        class="flex-1 py-3 text-xs font-black uppercase tracking-widest text-gray-400 border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-600 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 bg-orange-600 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-orange-700 shadow-lg transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <x-delete-modal />

    <script>
        // Toggle Variant Row
        function toggleVariant(id) {
            document.getElementById('variant-row-' + id).classList.toggle('hidden');
            document.getElementById('arrow-' + id).classList.toggle('rotate-180');
        }

        // Toggle All Variants (Select All)
        function toggleAllVariants(checkbox, productId) {
            const checkboxes = document.querySelectorAll('.variant-row-' + productId + ' .variant-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
            updateDeleteButton(productId);
        }

        // Update Delete Button (Show/Hide based on selection)
        function updateDeleteButton(productId) {
            const checkboxes = document.querySelectorAll('.variant-row-' + productId + ' .variant-checkbox');
            const deleteBtn = document.getElementById('deleteSelectedBtn-' + productId);
            let checkedCount = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) checkedCount++;
            });

            if (checkedCount > 0) {
                deleteBtn.classList.remove('hidden');
                deleteBtn.innerHTML = '<i class="fas fa-trash-alt mr-1"></i> Hapus Terpilih (' + checkedCount + ')';
            } else {
                deleteBtn.classList.add('hidden');
            }
        }

        // Delete Selected Variants
        function deleteSelectedVariants(productId) {
            const checkboxes = document.querySelectorAll('.variant-row-' + productId + ' .variant-checkbox:checked');

            if (checkboxes.length === 0) {
                alert('Pilih setidaknya 1 variant untuk dihapus!');
                return;
            }

            const variantIds = Array.from(checkboxes).map(cb => cb.value);

            if (confirm('Yakin ingin menghapus ' + variantIds.length + ' variant yang dipilih?')) {
                fetch('{{ route('variants.batchDelete') }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ids: variantIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus!');
                    });
            }
        }

        // Product Modal
        function openProductModal(mode, data = null) {
            const modal = document.getElementById('productModal');
            const form = document.getElementById('productForm');
            const title = document.getElementById('modalTitle');
            const methodField = document.getElementById('formMethod');

            if (mode === 'create') {
                title.textContent = 'New Product';
                form.action = '{{ route('products.store') }}';
                methodField.value = 'POST';
                form.reset();
            } else {
                title.textContent = 'Edit Product';
                form.action = '{{ url('products') }}/' + data.id;
                methodField.value = 'PUT';

                document.getElementById('productName').value = data.name;
                document.getElementById('productCategory').value = data.category_id || '';
                document.getElementById('productBrand').value = data.brand_id || '';
                document.getElementById('productStatus').value = data.status;
                document.getElementById('productDesc').value = data.description || '';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeProductModal() {
            const modal = document.getElementById('productModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Update Size Buttons Style (untuk Create Mode - multi select)
        function updateSizeButtons() {
            const input = document.getElementById('variant_sizes');
            const selected = input.value === '' ? [] : input.value.split(',').map(s => s.trim()).filter(s => s !== '');

            document.querySelectorAll('.size-btn').forEach(btn => {
                const size = btn.dataset.size;
                if (selected.includes(size)) {
                    btn.classList.remove('border-gray-200', 'text-gray-600', 'bg-white');
                    btn.classList.add('bg-orange-600', 'border-orange-600', 'text-white');
                } else {
                    btn.classList.remove('bg-orange-600', 'border-orange-600', 'text-white');
                    btn.classList.add('border-gray-200', 'text-gray-600', 'bg-white');
                }
            });
        }

        // Toggle Size (untuk Create Mode)
        function toggleSize(size) {
            // Jangan berfungsi jika dalam mode edit
            if (document.getElementById('variant_sizes_manual').disabled) return;

            const input = document.getElementById('variant_sizes');
            const manualInput = document.getElementById('variant_sizes_manual');
            let current = input.value === '' ? [] : input.value.split(',').map(s => s.trim()).filter(s => s !== '');

            if (current.includes(size)) {
                current = current.filter(s => s !== size);
            } else {
                current.push(size);
            }

            current.sort((a, b) => parseInt(a) - parseInt(b));

            const newValue = current.join(', ');
            input.value = newValue;
            manualInput.value = newValue;

            updateSizeButtons();
        }

        // Add Size (alias)
        function addSize(size) {
            toggleSize(size);
        }

        // Variant Modal - CREATE
        function openVariantModal(productId) {
            const modal = document.getElementById('variantModal');
            const form = document.getElementById('variantForm');
            const sizeContainer = document.getElementById('sizeContainer');

            document.getElementById('variantModalTitle').textContent = 'Add Variant';
            document.getElementById('variant_product_id').value = productId;
            form.action = '{{ route('variants.store') }}';
            document.getElementById('methodField').value = 'POST';

            // Reset all fields
            document.getElementById('variant_color').value = '';
            document.getElementById('variant_sizes').value = '';
            document.getElementById('variant_sizes_manual').value = '';
            document.getElementById('variant_sizes_manual').disabled = false;
            document.getElementById('variant_sizes_manual').placeholder = 'Atau ketik: 36, 37, 38';
            document.getElementById('variant_sku').value = '';
            document.getElementById('variant_stock').value = '10';
            document.getElementById('variant_price').value = '';
            document.getElementById('variant_discount_price').value = '';
            document.getElementById('colorPicker').value = '#000000';

            // Reset buttons - enable all untuk create mode
            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.classList.remove('bg-orange-600', 'border-orange-600', 'text-white');
                btn.classList.add('border-gray-200', 'text-gray-600', 'bg-white');
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Variant Modal - EDIT
        function openVariantEditModal(variant) {
            const modal = document.getElementById('variantModal');
            const form = document.getElementById('variantForm');
            const sizeContainer = document.getElementById('sizeContainer');
            const manualInput = document.getElementById('variant_sizes_manual');

            document.getElementById('variantModalTitle').textContent = 'Edit Variant';
            form.action = '/variants/' + variant.id;
            document.getElementById('methodField').value = 'PUT';

            document.getElementById('variant_product_id').value = variant.product_id;
            document.getElementById('variant_color').value = variant.color || '';
            document.getElementById('variant_sizes').value = variant.size || '';

            // Set manual input untuk edit mode ( Readonly, hanya 1 size)
            manualInput.value = variant.size || '';
            manualInput.disabled = true; // Disable karena edit hanya bisa 1 size
            manualInput.placeholder = 'Size: ' + (variant.size || '-');

            document.getElementById('variant_sku').value = variant.sku || '';
            document.getElementById('variant_stock').value = variant.stock || '10';
            document.getElementById('variant_price').value = variant.price || '';
            document.getElementById('variant_discount_price').value = variant.discount_price || '';

            // Update buttons - Pilih HANYA size variant yang diedit
            document.querySelectorAll('.size-btn').forEach(btn => {
                const size = btn.dataset.size;
                if (size === variant.size) {
                    btn.classList.remove('border-gray-200', 'text-gray-600', 'bg-white');
                    btn.classList.add('bg-orange-600', 'border-orange-600', 'text-white');
                } else {
                    btn.classList.remove('bg-orange-600', 'border-orange-600', 'text-white');
                    btn.classList.add('border-gray-200', 'text-gray-600', 'bg-white');
                }
                // Disable semua tombol size saat edit
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Close Variant Modal
        function closeVariantModal() {
            const modal = document.getElementById('variantModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Delete Modal
        function openDeleteModal(url) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');

            if (form) form.action = url;
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProductModal();
                closeVariantModal();
                closeDeleteModal();
            }
        });

        // Click outside to close
        document.addEventListener('click', function(e) {
            const productModal = document.getElementById('productModal');
            const variantModal = document.getElementById('variantModal');
            const deleteModal = document.getElementById('deleteModal');

            if (e.target === productModal) closeProductModal();
            if (e.target === variantModal) closeVariantModal();
            if (e.target === deleteModal) closeDeleteModal();
        });
    </script>
@endsection

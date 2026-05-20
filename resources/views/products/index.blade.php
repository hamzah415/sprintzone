{{-- resources/views/products/index.blade.php --}}

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
                                        <span class="text-green-600 font-bold text-xs">
                                            {{ $product->variants->sum('stock') }} pcs
                                        </span>
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
                                                <button onclick="openVariantModal({{ $product->id }})"
                                                    class="btn btn-sprint px-3 py-1 text-xs">+ Add</button>
                                            </div>
                                            @if ($product->variants->count() > 0)
                                                <table class="w-full text-xs">
                                                    <thead>
                                                        <tr class="border-b text-gray-400">
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
                                                            <tr class="border-b">
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
                <input type="hidden" id="formMethod" value="POST">

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

    {{-- VARIANT MODAL --}}
    <div id="variantModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-sm shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-black italic uppercase tracking-widest text-lg" id="variantModalTitle">Add
                    Variant</h3>
                <button onclick="closeVariantModal()"
                    class="text-gray-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>
            <form id="variantForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div id="variantMethodField"></div>
                <input type="hidden" name="product_id" id="variant_product_id">

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Color</label>
                        <input type="text" name="color" id="variant_color"
                            class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3" required>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Size</label>
                        <input type="text" name="size" id="variant_size"
                            class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3" required>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">SKU</label>
                        <input type="text" name="sku" id="variant_sku"
                            class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Stock</label>
                        <input type="number" name="stock" id="variant_stock"
                            class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Price</label>
                        <input type="number" name="price" id="variant_price"
                            class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Discount</label>
                        <input type="number" name="discount_price" id="variant_discount_price"
                            class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Image</label>
                    <input type="file" name="image"
                        class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-xs file:font-black file:bg-gray-900 file:text-white hover:file:bg-gray-700 cursor:pointer">
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeVariantModal()"
                        class="px-6 py-2 text-xs font-black uppercase tracking-widest text-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-8 py-2 bg-orange-600 text-white text-xs font-black uppercase tracking-widest rounded-sm hover:bg-orange-700 shadow-md">Save
                        Data</button>
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

        // Product Modal - yang sudah diperbaiki
        function openProductModal(mode, data = null) {
            const modal = document.getElementById('productModal');
            const form = document.getElementById('productForm');
            const title = document.getElementById('modalTitle');
            const methodField = document.getElementById('formMethod');

            if (mode === 'create') {
                title.textContent = 'New Product';
                form.action = '{{ route('products.store') }}';
                methodField.value = 'POST';

                // Reset semua field
                document.getElementById('productName').value = '';
                document.getElementById('productCategory').value = '';
                document.getElementById('productBrand').value = '';
                document.getElementById('productStatus').value = 'active';
                document.getElementById('productDesc').value = '';
            } else {
                title.textContent = 'Edit Product';
                form.action = '/products/' + data.id;
                methodField.value = 'PUT';

                document.getElementById('productName').value = data.name;
                document.getElementById('productCategory').value = data.category_id || '';
                document.getElementById('productBrand').value = data.brand_id || '';
                document.getElementById('productStatus').value = data.status;
                document.getElementById('productDesc').value = data.description || '';
            }

            modal.classList.remove('hidden');
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        // Variant Modal
        function openVariantModal(productId) {
            const modal = document.getElementById('variantModal');
            const form = document.getElementById('variantForm');

            document.getElementById('variantModalTitle').textContent = 'Add Variant';
            document.getElementById('variant_product_id').value = productId;
            form.action = '{{ route('variants.store') }}';
            document.getElementById('variantMethodField').innerHTML = '@csrf';

            form.reset();
            modal.classList.remove('hidden');
        }

        function openVariantEditModal(variant) {
            const modal = document.getElementById('variantModal');
            const form = document.getElementById('variantForm');

            document.getElementById('variantModalTitle').textContent = 'Edit Variant';
            form.action = '/variants/' + variant.id;
            document.getElementById('variantMethodField').innerHTML = '@csrf @method('PUT')';

            document.getElementById('variant_product_id').value = variant.product_id;
            document.getElementById('variant_color').value = variant.color;
            document.getElementById('variant_size').value = variant.size;
            document.getElementById('variant_sku').value = variant.sku || '';
            document.getElementById('variant_stock').value = variant.stock;
            document.getElementById('variant_price').value = variant.price;
            document.getElementById('variant_discount_price').value = variant.discount_price || '';

            modal.classList.remove('hidden');
        }

        function closeVariantModal() {
            document.getElementById('variantModal').classList.add('hidden');
        }

        // Delete Modal (gunakan komponen delete-modal)
        // function openDeleteModal ada di components/delete-modal.blade.php

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProductModal();
                closeVariantModal();
            }
        });
    </script>
@endsection

{{-- resources/views/products/index.blade.php --}}

@extends('layouts.admin.app')

@section('content')
    <style>
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 50;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            margin: 20px;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .table-mobile {
                font-size: 12px;
            }
        }
    </style>

    <div class="container-fluid py-4">
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl md:text-3xl font-black italic uppercase tracking-tight">
                Product <span class="text-orange-500">Management</span>
            </h2>
            <button
                class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-3 rounded font-black uppercase text-sm shadow"
                onclick="openProductModal('create')">
                + New Product
            </button>
        </div>

        {{-- TABLE --}}
        <div class="bg-white shadow rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Act</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Product</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">SKU</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Price</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Disc</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Stock</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Weight</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Category</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Brand</th>
                            <th class="px-3 py-3 text-left text-xs uppercase tracking-widest text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="border-b bg-white hover:bg-gray-50">
                                {{-- ACTION --}}
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-1">
                                        <button onclick="toggleVariant({{ $product->id }})"
                                            class="w-7 h-7 rounded-full border flex items-center justify-center hover:bg-gray-100">
                                            <svg id="arrow-{{ $product->id }}" class="w-3 h-3 transition" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <button onclick='openProductModal("edit", @json($product))'
                                            class="w-7 h-7 rounded-full border flex items-center justify-center hover:bg-gray-100">✎</button>
                                        <button onclick="openDeleteModal('{{ route('products.destroy', $product->id) }}')"
                                        class="w-7 h-7 rounded-full border border-red-200 text-red-500 flex items-center justify-center hover:bg-red-50">×</button>
                                    </div>
                                </td>

                                {{-- PRODUCT --}}
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-2">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="w-10 h-10 rounded object-cover border">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded border bg-gray-100 flex items-center justify-center text-[8px] text-gray-400">
                                                No</div>
                                        @endif
                                        <div>
                                            <span class="font-bold text-gray-900 text-sm block">{{ $product->name }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $product->variants->count() }}
                                                variant</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- SKU --}}
                                <td class="px-3 py-3 text-xs text-gray-500">{{ $product->sku ?? '-' }}</td>

                                {{-- PRICE --}}
                                <td class="px-3 py-3 font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>

                                {{-- DISCOUNT --}}
                                <td class="px-3 py-3 text-red-500 text-xs font-bold">
                                    {{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}
                                </td>

                                {{-- STOCK --}}
                                <td class="px-3 py-3">
                                    <span class="text-green-600 font-bold text-xs">{{ $product->stock }} pcs</span>
                                </td>

                                {{-- WEIGHT --}}
                                <td class="px-3 py-3 text-xs text-gray-500">
                                    {{ $product->weight ? $product->weight . 'gr' : '-' }}</td>

                                {{-- CATEGORY --}}
                                <td class="px-3 py-3">
                                    <span
                                        class="bg-black text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $product->category->name ?? '-' }}</span>
                                </td>

                                {{-- BRAND --}}
                                <td class="px-3 py-3 text-xs text-gray-600 font-medium">{{ $product->brand->name ?? '-' }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-3 py-3">
                                    @if ($product->status == 'active')
                                        <span
                                            class="bg-green-600 text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase">Active</span>
                                    @else
                                        <span
                                            class="bg-gray-500 text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase">Inactive</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- VARIANT ROW --}}
                            <tr id="variant-row-{{ $product->id }}" class="hidden bg-gray-50">
                                <td colspan="10" class="p-4">
                                    <div class="bg-white rounded-lg border p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <h4 class="font-bold uppercase text-xs text-gray-700">Product Variants</h4>
                                                <p class="text-[10px] text-gray-400">Manage variations for this product</p>
                                            </div>
                                            <button onclick="openVariantModal({{ $product->id }})"
                                                class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-[10px] font-bold">+
                                                Add</button>
                                        </div>
                                        @if ($product->variants->count() > 0)
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="border-b text-gray-400">
                                                        <th class="py-2 text-left">Img</th>
                                                        <th class="py-2 text-left">Color</th>
                                                        <th class="py-2 text-left">Size</th>
                                                        <th class="py-2 text-left">SKU</th>
                                                        <th class="py-2 text-left">Price</th>
                                                        <th class="py-2 text-left">Disc</th>
                                                        <th class="py-2 text-left">Stock</th>
                                                        <th class="py-2 text-left">Act</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->variants as $variant)
                                                        <tr class="border-b">
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
                                                            <td class="py-2 font-bold text-green-600">{{ $variant->stock }}
                                                            </td>
                                                            <td class="py-2">
                                                                <div class="flex gap-1">
                                                                    <button
                                                                        onclick='openVariantEditModal(@json($variant))'
                                                                        class="w-6 h-6 rounded border hover:bg-gray-100">✎</button>
                                                                    <button
                                                                        onclick="openDeleteModal('{{ route('variants.destroy', $variant->id) }}')"
                                                                        class="w-6 h-6 rounded border border-red-200 text-red-500 hover:bg-red-50">×</button>
                                                                </div>
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
                                <td colspan="8" class="py-20 text-center text-gray-400">No Product Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PRODUCT MODAL --}}
    <div id="productModal" class="modal">
        <div class="modal-overlay" onclick="closeProductModal()"></div>
        <div class="modal-content">
            <div class="p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold" id="modalTitle">New Product</h3>
                    <button onclick="closeProductModal()" class="text-2xl">&times;</button>
                </div>
                <form id="productForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold mb-1">Name</label>
                            <input type="text" name="name" id="productName"
                                class="w-full border rounded p-2 text-sm" required>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold mb-1">Category</label>
                                <select name="category_id" id="productCategory"
                                    class="w-full border rounded p-2 text-sm">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1">Brand</label>
                                <select name="brand_id" id="productBrand" class="w-full border rounded p-2 text-sm">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Image</label>
                            <input type="file" name="image" class="w-full border rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Status</label>
                            <select name="status" id="productStatus" class="w-full border rounded p-2 text-sm">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Description</label>
                            <textarea name="description" id="productDesc" rows="3" class="w-full border rounded p-2 text-sm"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button type="submit"
                            class="flex-1 bg-orange-600 text-white py-2 rounded font-bold text-sm">Save</button>
                        <button type="button" onclick="closeProductModal()"
                            class="px-4 border rounded text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- VARIANT MODAL --}}
    <div id="variantModal" class="fixed inset-0 bg-black/60 hidden z-50 overflow-y-auto">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-xl">
                <div class="bg-gray-900 px-4 py-3 flex justify-between items-center">
                    <h3 class="text-white font-bold uppercase text-xs tracking-widest" id="variantModalTitle">Add Variant
                    </h3>
                    <button onclick="closeVariantModal()" class="text-white text-xl">&times;</button>
                </div>
                <form id="variantForm" method="POST" enctype="multipart/form-data" class="p-4">
                    @csrf
                    <div id="variantMethodField"></div>
                    <input type="hidden" name="product_id" id="variant_product_id">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold mb-1">Color</label>
                            <input type="text" name="color" id="variant_color"
                                class="w-full border rounded p-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Size</label>
                            <input type="text" name="size" id="variant_size"
                                class="w-full border rounded p-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">SKU</label>
                            <input type="text" name="sku" id="variant_sku"
                                class="w-full border rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Stock</label>
                            <input type="number" name="stock" id="variant_stock"
                                class="w-full border rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Price</label>
                            <input type="number" name="price" id="variant_price"
                                class="w-full border rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Discount</label>
                            <input type="number" name="discount_price" id="variant_discount_price"
                                class="w-full border rounded p-2 text-sm">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs font-bold mb-1">Image</label>
                        <input type="file" name="image" class="w-full border rounded p-2 text-sm">
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button type="submit"
                            class="flex-1 bg-orange-600 text-white py-2 rounded font-bold text-sm">Save</button>
                        <button type="button" onclick="closeVariantModal()"
                            class="px-4 border rounded text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div id="deleteModal" class="modal">
        <div class="modal-overlay" onclick="closeDeleteModal()"></div>
        <div class="modal-content max-w-xs">
            <div class="p-5">
                <h3 class="text-base font-bold mb-2">Delete?</h3>
                <p class="text-gray-500 text-xs mb-3">This action cannot be undone.</p>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-red-500 text-white py-2 rounded font-bold text-sm">Delete</button>
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 border rounded text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle Variant Row
        function toggleVariant(id) {
            document.getElementById('variant-row-' + id).classList.toggle('hidden');
            document.getElementById('arrow-' + id).classList.toggle('rotate-180');
        }

        // Product Modal
        function openProductModal(mode, data = null) {
            const modal = document.getElementById('productModal');
            const form = document.getElementById('productForm');
            const title = document.getElementById('modalTitle');

            if (mode === 'create') {
                title.textContent = 'New Product';
                form.action = '{{ route('products.store') }}';
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('productName').value = '';
                document.getElementById('productDesc').value = '';
                document.getElementById('productStatus').value = 'active';
            } else {
                title.textContent = 'Edit Product';
                form.action = '/products/' + data.id;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('productName').value = data.name;
                document.getElementById('productDesc').value = data.description || '';
                document.getElementById('productStatus').value = data.status;
            }

            modal.classList.add('active');
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.remove('active');
        }

        // Variant Modal
        function openVariantModal(productId) {
            const modal = document.getElementById('variantModal');
            const form = document.getElementById('variantForm');

            document.getElementById('variantModalTitle').textContent = 'Add Variant';
            document.getElementById('variant_product_id').value = productId;
            form.action = '{{ route('variants.store') }}';
            document.getElementById('variantMethodField').innerHTML = '@csrf';

            // Clear form
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

        // Delete Modal
        function openDeleteModal(url) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProductModal();
                closeVariantModal();
                closeDeleteModal();
            }
        });
    </script>
    </body>
@endsection

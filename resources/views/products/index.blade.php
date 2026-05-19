{{-- resources/views/products/index.blade.php --}}

@extends('layouts.admin.app')

@section('content')
    <style>
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

        .product-image {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        .badge-active {
            background: #198754;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
        }

        .badge-inactive {
            background: #6c757d;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
        }

        .stock-good {
            color: #198754;
            font-weight: bold;
        }

        .stock-low {
            color: #dc3545;
            font-weight: bold;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8">

            <h2 class="text-1xl md:text-2xl font-black italic uppercase tracking-tighter">

                PRODUCT
                <span class="text-orange-500">
                    MANAGEMENT
                </span>

            </h2>

            <button class="btn btn-sprint px-4 shadow-sm" onclick="openProductModal('create')">

                <i class="fas fa-plus-circle mr-2"></i>
                NEW PRODUCT

            </button>

        </div>

        {{-- CARD --}}
        <div class="card card-sprint">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        {{-- TABLE HEADER --}}
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">
                                    Action
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Product
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Category
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Brand
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Price
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Stock
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        {{-- TABLE BODY --}}
                        <tbody class="divide-y divide-gray-50">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition duration-150">

                                    {{-- ACTION --}}
                                    <td class="px-6 py-4 text-center text-sm">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- EDIT --}}
                                            <button onclick='openProductModal("edit", @json($product))'
                                                class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-100 transition shadow-sm">

                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>

                                                </svg>

                                            </button>

                                            {{-- DELETE --}}
                                            <button type="submit"
                                                onclick="openDeleteModal('{{ route('products.destroy', $product->id) }}','Product <b>{{ $product->name }}</b> will be deleted.')"
                                                class="w-9 h-9 flex items-center justify-center rounded-full border border-red-100 hover:bg-red-50 transition shadow-sm">

                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />

                                                </svg>

                                            </button>

                                        </div>
                                    </td>
                                    {{-- PRODUCT --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-4">

                                            {{-- IMAGE --}}
                                            <div>

                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        alt="{{ $product->name }}"
                                                        class="w-14 h-14 rounded object-cover border border-gray-200 shadow-sm">
                                                @else
                                                    <div
                                                        class="w-14 h-14 rounded bg-gray-100 border border-gray-200 flex items-center justify-center text-[10px] text-gray-400">

                                                        No Image

                                                    </div>
                                                @endif

                                            </div>

                                            {{-- INFO --}}
                                            <div>

                                                <div class="font-black uppercase text-sm text-gray-800 tracking-wide">

                                                    {{ $product->name }}

                                                </div>

                                                <div class="text-xs text-gray-400 mt-1">

                                                    SKU:
                                                    {{ $product->sku ?? '-' }}

                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- CATEGORY --}}
                                    <td class="px-6 py-4">

                                        <span
                                            class="px-3 py-1 text-[10px] font-black uppercase rounded bg-gray-900 text-white tracking-wider">

                                            {{ $product->category->name ?? 'No Category' }}

                                        </span>

                                    </td>

                                    {{-- BRAND --}}
                                    <td class="px-6 py-4 text-sm text-gray-500 font-semibold">

                                        {{ $product->brand->name ?? '-' }}

                                    </td>

                                    {{-- PRICE --}}
                                    <td class="px-6 py-4">

                                        <div class="font-black text-gray-800 text-sm">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                        @if ($product->discount_price)
                                            <div class="text-[11px] text-red-500 font-bold mt-1">
                                                Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>
                                    {{-- STOCK --}}
                                    <td class="px-6 py-4">
                                        @if ($product->stock > 10)
                                            <span class="text-green-600 font-black text-xs uppercase tracking-wide">
                                                {{ $product->stock }} Ready
                                            </span>
                                        @elseif($product->stock > 0)
                                            <span class="text-yellow-500 font-black text-xs uppercase tracking-wide">
                                                {{ $product->stock }} Low
                                            </span>
                                        @else
                                            <span class="text-red-500 font-black text-xs uppercase tracking-wide">
                                                Out Of Stock
                                            </span>
                                        @endif
                                    </td>
                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">
                                        @if ($product->status == 'active')
                                            <span
                                                class="px-3 py-1 text-[10px] font-black uppercase rounded bg-green-600 text-white tracking-widest">
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-[10px] font-black uppercase rounded bg-gray-500 text-white tracking-widest">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                                            <p class="text-gray-400 font-medium text-sm tracking-wide">
                                                There is no product data yet.
                                            </p>
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

    {{-- MODAL PRODUCT --}}
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-60 hidden overflow-y-auto z-50">
        <div class="min-h-screen flex items-start justify-center p-6 pt-20">
            <div class="bg-white rounded-sm shadow-2xl w-full max-w-4xl overflow-hidden">
                {{-- HEADER --}}
                <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                    <h3 id="modalTitle" class="text-white font-black italic uppercase tracking-widest text-lg">
                        Add New Product
                    </h3>
                    <button onclick="closeProductModal()" class="text-gray-400 hover:text-white text-2xl font-bold">
                        &times;
                    </button>
                </div>

                {{-- FORM --}}
                <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6">
                    @csrf
                    <div id="methodField"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- PRODUCT NAME --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Product Name
                            </label>
                            <input type="text" name="name" id="product_name" required
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                        </div>

                        {{-- SKU --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                SKU
                            </label>
                            <input type="text" name="sku" id="product_sku"
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                        </div>

                        {{-- CATEGORY --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Category
                            </label>

                            <select name="category_id" id="product_category"
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                                <option value="">
                                    Select Category
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BRAND --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Brand
                            </label>

                            <select name="brand_id" id="product_brand"
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                                <option value="">
                                    Select Brand
                                </option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- COMPANY --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Company
                            </label>
                            <select name="company_id" id="product_company"
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                                <option value="">
                                    Select Company
                                </option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Status
                            </label>
                            <select name="status" id="product_status"
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                                <option value="active">
                                    Active
                                </option>
                                <option value="inactive">
                                    Inactive
                                </option>
                            </select>
                        </div>

                        {{-- PRICE --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Price
                            </label>
                            <input type="number" name="price" id="product_price" required
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                        </div>
                        {{-- DISCOUNT --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Discount Price
                            </label>
                            <input type="number" name="discount_price" id="product_discount_price"
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                        </div>
                        {{-- STOCK --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Stock
                            </label>
                            <input type="number" name="stock" id="product_stock" required
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                        </div>
                        {{-- WEIGHT --}}
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                                Weight (gram)
                            </label>
                            <input type="number" name="weight" id="product_weight"
                                class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mt-5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                            Product Description
                        </label>
                        <textarea name="description" id="product_description" rows="4"
                            class="w-full border-gray-200 border-2 rounded-sm text-sm font-medium p-3"></textarea>
                    </div>
                    {{-- IMAGE --}}
                    <div class="mt-5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                            Upload Product Image
                        </label>

                        <input type="file" name="image" accept="image/*"
                            class="w-full text-xs text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-sm file:border-0
                              file:text-xs file:font-black
                              file:bg-gray-900 file:text-white
                              hover:file:bg-gray-700 cursor:pointer">
                    </div>
                    {{-- FOOTER --}}
                    <div class="flex items-center justify-end mt-8">
                        {{-- RIGHT --}}
                        <div class="flex gap-3">
                            <button type="button" onclick="closeProductModal()"
                                class="px-6 py-2 text-xs font-black uppercase tracking-widest text-gray-400">
                                Cancel
                            </button>

                            <button type="submit"
                                class="px-8 py-2 bg-orange-600 text-white text-xs font-black uppercase tracking-widest rounded-sm hover:bg-orange-700 shadow-md">
                                Save Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openProductModal(mode, product = null) {
            const modal = document.getElementById('productModal');
            const form = document.getElementById('productForm');
            const title = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');
            modal.classList.remove('hidden');
            if (mode === 'edit') {
                title.innerText = 'Edit Product Details';
                form.action = `/products/${product.id}`;
                methodField.innerHTML = `@method('PUT')`;
                document.getElementById('product_name').value = product.name ?? '';
                document.getElementById('product_sku').value = product.sku ?? '';
                document.getElementById('product_price').value = product.price ?? '';
                document.getElementById('product_discount_price').value = product.discount_price ?? '';
                document.getElementById('product_stock').value = product.stock ?? '';
                document.getElementById('product_weight').value = product.weight ?? '';
                document.getElementById('product_description').value = product.description ?? '';
                document.getElementById('product_status').value = product.status ?? 'active';
                document.getElementById('product_category').value = product.category_id ?? '';
                document.getElementById('product_brand').value = product.brand_id ?? '';
                document.getElementById('product_company').value = product.company_id ?? '';
            } else {
                title.innerText = 'Add New Product';
                form.action = "{{ route('products.store') }}";
                methodField.innerHTML = '';
                form.reset();
            }
        }

        function closeProductModal() {
            document.getElementById('productModal')
                .classList.add('hidden');
        }
    </script>
    <x-delete-modal />
@endsection

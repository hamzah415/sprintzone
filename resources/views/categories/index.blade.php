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

        .badge-admin {
            background-color: #212529;
            color: white;
        }

        .badge-user {
            background-color: #ffc107;
            color: #212529;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-1xl md:text-2xl font-black italic uppercase tracking-tighter">CATEGORY <span
                    class="text-orange-500">MANAGEMENT</span></h2>
            <button class="btn btn-sprint px-4 shadow-sm" onclick="openModal('add')">
                <i class="fas fa-plus-circle mr-2"></i> NEW CATEGORY
            </button>
        </div>

        <div class="card card-sprint">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Action</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Category
                                    Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Slug</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Description
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Created</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Updated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            {{-- EDIT --}}
                                            <button onclick='openModal("edit", @json($category))'
                                                class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            {{-- DELETE --}}
                                            <button type="button"
                                                onclick="openDeleteModal('{{ route('categories.destroy', $category->id) }}', 'Category <b>{{ $category->name }}</b> will be deleted.')"
                                                class="w-9 h-9 flex items-center justify-center rounded-full border border-red-200 text-red-500 hover:bg-red-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-800 uppercase text-sm">{{ $category->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-400 font-medium">
                                        {{ $category->slug }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 text-[10px] font-black uppercase rounded bg-gray-800 text-white tracking-tighter">
                                            {{ Str::limit($category->description ?? 'NO DESCRIPTION', 30) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-gray-700">{{ $category->creator->name ?? '-' }}</span>
                                            <span
                                                class="text-xs text-gray-400">{{ $category->created_at->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-gray-700">{{ $category->updater->name ?? '-' }}</span>
                                            <span
                                                class="text-xs text-gray-400">{{ $category->updated_at ? $category->updated_at->format('d M Y') : '-' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-tags text-gray-300 text-5xl mb-4"></i>
                                            <p class="text-gray-400 font-medium text-sm tracking-wide">There is no category
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

    {{-- MODAL BOX --}}
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-sm shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitle" class="text-white font-black italic uppercase tracking-widest text-lg">Add New Category
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>

            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="p-6">
                @csrf
                <div id="methodField"></div>

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Category
                        Name</label>
                    <input type="text" name="name" id="cat_name" required
                        class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                </div>

                <div class="mb-5">
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Description</label>
                    <textarea name="description" id="cat_description" rows="3"
                        class="w-full border-gray-200 border-2 rounded-sm text-sm font-medium p-3"></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-2 text-xs font-black uppercase tracking-widest text-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-8 py-2 bg-orange-600 text-white text-xs font-black uppercase tracking-widest rounded-sm hover:bg-orange-700 shadow-md">Save
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode, category = null) {
            const modal = document.getElementById('categoryModal');
            const form = document.getElementById('categoryForm');
            const methodField = document.getElementById('methodField');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');

            if (mode === 'edit') {
                title.innerText = 'Edit Category Details';
                form.action = `/categories/${category.id}`;
                methodField.innerHTML = `@method('PUT')`;
                document.getElementById('cat_name').value = category.name;
                document.getElementById('cat_description').value = category.description;
            } else {
                title.innerText = 'Add New Category';
                form.action = "{{ route('categories.store') }}";
                methodField.innerHTML = '';
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }
    </script>
    <x-delete-modal />
@endsection

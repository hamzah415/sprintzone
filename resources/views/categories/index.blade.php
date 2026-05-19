@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black italic uppercase tracking-tighter">CATEGORY <span
                    class="text-orange-500">MANAGEMENT</span></h2>
            <button onclick="openModal('add')"
                class="bg-orange-500 text-white font-bold py-2 px-4 rounded shadow-sm hover:bg-orange-600">
                <i class="fas fa-plus-circle mr-2"></i> NEW CATEGORY
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden text-left">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100 uppercase text-xs text-gray-500 font-bold tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Category Name</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Created By</th>
                        <th class="px-6 py-4">Created Date</th>
                        <th class="px-6 py-4">Updated By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-2">

                                    {{-- EDIT --}}
                                    <button onclick='openModal("edit", @json($category))'
                                        class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>

                                    </button>

                                    {{-- DELETE --}}
                                    <button type="button"
                                        onclick="openDeleteModal(
                                        '{{ route('categories.destroy', $category->id) }}',
                                        'Category <b>{{ $category->name }}</b> will be deleted.')"
                                        class="w-9 h-9 flex items-center justify-center rounded-full border border-red-100 text-red-500 hover:bg-red-50 transition">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />

                                        </svg>

                                    </button>

                                </div>

                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800 uppercase">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-gray-400 text-sm italic">{{ $category->slug }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $category->creator->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $category->created_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                {{ $category->updater->name ?? '-' }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-tags text-gray-200 text-6xl mb-4"></i>
                                    <p class="text-gray-400">There is no category data yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-sm w-full max-w-md shadow-2xl overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitle" class="text-white font-black italic uppercase text-lg">ADD CATEGORY</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white text-2xl">&times;</button>
            </div>
            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="p-6 text-left">
                @csrf
                <div id="methodField"></div>
                <div class="mb-4">
                    <label class="block text-[10px] font-black uppercase text-gray-500 tracking-widest mb-2">Category
                        Name</label>
                    <input type="text" name="name" id="cat_name" required
                        class="w-full border-gray-200 border-2 rounded-sm p-3 font-bold text-sm">
                </div>
                <div class="mb-4">
                    <label
                        class="block text-[10px] font-black uppercase text-gray-500 tracking-widest mb-2">Description</label>
                    <textarea name="description" id="cat_description" rows="3"
                        class="w-full border-gray-200 border-2 rounded-sm p-3 text-sm"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal()"
                        class="text-xs font-bold text-gray-400 uppercase">Cancel</button>
                    <button type="submit"
                        class="bg-orange-600 text-white px-6 py-2 rounded-sm text-xs font-black uppercase tracking-widest">Save
                        Category</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode, category = null) {
            const modal = document.getElementById('categoryModal');
            const form = document.getElementById('categoryForm');
            modal.classList.remove('hidden');
            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'EDIT CATEGORY';
                form.action = `/categories/${category.id}`;
                document.getElementById('methodField').innerHTML = `@method('PUT')`;
                document.getElementById('cat_name').value = category.name;
                document.getElementById('cat_description').value = category.description;
            } else {
                document.getElementById('modalTitle').innerText = 'ADD CATEGORY';
                form.action = "{{ route('categories.store') }}";
                document.getElementById('methodField').innerHTML = '';
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }
    </script>
    <x-delete-modal />
@endsection

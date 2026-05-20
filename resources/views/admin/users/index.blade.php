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
        <div class="d-flex justify-content-between align-items-center mb-8">
            <h2 class="text-1xl md:text-2xl font-black italic uppercase tracking-tighter">USER <span
                    class="text-orange-500">MANAGEMENT</span></h2>
            <button class="btn btn-sprint px-4 shadow-sm" onclick="openModal('add')">
                <i class="fas fa-plus-circle mr-2"></i> NEW USER
            </button>
        </div>

        <div class="card card-sprint">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Action</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Full Name
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Email</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Company</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Role</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Created</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Updated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            {{-- EDIT --}}
                                            <button onclick='openModal("edit", @json($user))'
                                                class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            {{-- DELETE --}}
                                            <button type="button"
                                                onclick="openDeleteModal('{{ route('admin.users.destroy', $user->id) }}', 'User <b>{{ $user->name }}</b> will be deleted.')"
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
                                        <span class="font-bold text-gray-800 text-sm">{{ $user->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 text-[10px] font-black uppercase rounded bg-gray-100 text-gray-600 tracking-tighter">
                                            {{ $user->company->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->role == 'admin')
                                            <span
                                                class="px-3 py-1 text-[10px] font-black uppercase rounded bg-gray-800 text-white tracking-tighter">ADMIN</span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-[10px] font-black uppercase rounded bg-yellow-500 text-gray-900 tracking-tighter">USER</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-700">System</span>
                                            <span
                                                class="text-xs text-gray-400">{{ $user->created_at->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-700">-</span>
                                            <span
                                                class="text-xs text-gray-400">{{ $user->updated_at->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                            <p class="text-gray-400 font-medium text-sm tracking-wide">There is no user data
                                                yet.</p>
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
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-sm shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitle" class="text-white font-black italic uppercase tracking-widest text-lg">Add New User</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>

            <form id="userForm" action="{{ route('admin.users.store') }}" method="POST" class="p-6">
                @csrf
                <div id="methodField"></div>

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Full
                        Name</label>
                    <input type="text" name="name" id="user_name" required
                        class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                </div>

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Email
                        Address</label>
                    <input type="email" name="email" id="user_email" required
                        class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                </div>

                <div class="mb-5">
                    <label
                        class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Company</label>
                    <select name="company_id" id="user_company"
                        class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                        <option value="">Select Company</option>
                        @foreach (\App\Models\Company::all() as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Role</label>
                    <select name="role" id="user_role"
                        class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
                        <option value="user">USER</option>
                        <option value="admin">ADMINISTRATOR</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                        Password <span class="text-gray-400 font-normal">(Leave blank if not replaced)</span>
                    </label>
                    <input type="password" name="password" id="user_password"
                        class="w-full border-gray-200 border-2 rounded-sm text-sm font-bold p-3">
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
        function openModal(mode, user = null) {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const methodField = document.getElementById('methodField');
            const title = document.getElementById('modalTitle');

            modal.classList.remove('hidden');

            if (mode === 'edit') {
                title.innerText = 'Edit User Details';
                form.action = `/admin/users/${user.id}`;
                methodField.innerHTML = `@method('PUT')`;
                document.getElementById('user_name').value = user.name;
                document.getElementById('user_email').value = user.email;
                document.getElementById('user_company').value = user.company_id || '';
                document.getElementById('user_role').value = user.role;
                document.getElementById('user_password').value = '';
            } else {
                title.innerText = 'Add New User';
                form.action = "{{ route('admin.users.store') }}";
                methodField.innerHTML = '';
                form.reset();
            }
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }
    </script>
    <x-delete-modal />
@endsection

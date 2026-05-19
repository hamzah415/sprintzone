@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">My Profile</h1>
        <p class="text-gray-500 text-sm">Kelola informasi akunmu</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border rounded-xl p-6">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- PHOTO PROFILE --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="relative">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-20 h-20 rounded-full object-cover">
                    @else
                        <div class="w-20 h-20 rounded-full bg-orange-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition">
                        Ganti Foto
                        <input type="file" name="photo" accept="image/*" class="hidden" onchange="this.form.submit()">
                    </label>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG maks 2MB</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required
                        class="w-full border rounded-lg px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled
                        class="w-full bg-gray-100 border rounded-lg px-4 py-2.5 text-sm text-gray-500">
                </div>

                <div>
                    <label class="text-xs text-gray-500 mb-1 block">No. WhatsApp</label>
                    <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="0812..."
                        class="w-full border rounded-lg px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Alamat</label>
                    <textarea name="address" rows="3" placeholder="Jl. ..."
                        class="w-full border rounded-lg px-4 py-2.5 text-sm resize-none">{{ auth()->user()->address ?? '' }}</textarea>
                </div>
            </div>

            <button type="submit"
                class="w-full mt-6 bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-medium text-sm transition">
                Simpan Perubahan
            </button>
        </form>
    </div>

</div>
@endsection
@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl md:text-3xl font-black italic uppercase tracking-tight">
                LAPORAN <span class="text-orange-500">USER</span>
            </h2>
            <p class="text-sm text-gray-400 mt-1">Data user dan history belanja</p>
        </div>
        <a href="{{ route('laporan.user.export') }}" 
            class="px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-black uppercase">
            <i class="fas fa-download mr-1"></i> EXPORT CSV
        </a>
    </div>

    {{-- FILTER --}}
    <div class="flex gap-2 mb-4">
        <a href="{{ route('laporan.user', ['filter' => 'today']) }}"
            class="px-3 py-1 rounded-lg text-xs font-black uppercase {{ $filter == 'today' ? 'bg-orange-600 text-white' : 'bg-gray-100' }}">
            HARI INI
        </a>
        <a href="{{ route('laporan.user', ['filter' => 'week']) }}"
            class="px-3 py-1 rounded-lg text-xs font-black uppercase {{ $filter == 'week' ? 'bg-orange-600 text-white' : 'bg-gray-100' }}">
            MINGGU INI
        </a>
        <a href="{{ route('laporan.user', ['filter' => 'month']) }}"
            class="px-3 py-1 rounded-lg text-xs font-black uppercase {{ $filter == 'month' ? 'bg-orange-600 text-white' : 'bg-gray-100' }}">
            BULAN INI
        </a>
        <a href="{{ route('laporan.user', ['filter' => 'all']) }}"
            class="px-3 py-1 rounded-lg text-xs font-black uppercase {{ $filter == 'all' ? 'bg-orange-600 text-white' : 'bg-gray-100' }}">
            SEMUA
        </a>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="bg-blue-500 text-white p-4 rounded-lg">
            <p class="text-xs uppercase">Total User</p>
            <p class="text-2xl font-black">{{ $totalUser }}</p>
        </div>
        <div class="bg-green-600 text-white p-4 rounded-lg">
            <p class="text-xs uppercase">Total Order</p>
            <p class="text-2xl font-black">{{ $totalOrder }}</p>
        </div>
        <div class="bg-purple-600 text-white p-4 rounded-lg">
            <p class="text-xs uppercase">Total Belanja</p>
            <p class="text-2xl font-black">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-left text-xs font-bold">#</th>
                    <th class="p-4 text-left text-xs font-bold">Nama</th>
                    <th class="p-4 text-left text-xs font-bold">Email</th>
                    <th class="p-4 text-right text-xs font-bold">Total Order</th>
                    <th class="p-4 text-right text-xs font-bold">Total Belanja</th>
                    <th class="p-4 text-right text-xs font-bold">Terakhir Beli</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr class="border-t">
                        <td class="p-4">{{ $index + 1 }}</td>
                        <td class="p-4 font-bold">{{ $user->name }}</td>
                        <td class="p-4 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="p-4 text-right font-bold">{{ $user->orders_count }}</td>
                        <td class="p-4 text-right font-bold text-green-600">
                            Rp {{ number_format($user->orders_sum_total_price ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-right text-sm text-gray-400">
                            {{ $user->orders()->latest('created_at')->first()?->created_at->format('d M Y') ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-8 text-center text-gray-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
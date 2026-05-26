@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <h2 class="text-2xl md:text-3xl font-black italic uppercase tracking-tight">
        LAPORAN <span class="text-orange-500">PENJUALAN</span>
    </h2>

    <div class="card mt-4">
        <div class="card-body p-6">
            <form action="{{ route('laporan.penjualan.report') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                            Tanggal Mulai
                        </label>
                        <input type="date" name="start_date" required
                            class="w-full border-2 border-gray-200 rounded-lg text-sm p-3">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                            Tanggal Akhir
                        </label>
                        <input type="date" name="end_date" required
                            class="w-full border-2 border-gray-200 rounded-lg text-sm p-3">
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="w-full bg-orange-600 text-white py-3 rounded-lg font-black uppercase text-sm">
                            <i class="fas fa-search mr-2"></i> CARI
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
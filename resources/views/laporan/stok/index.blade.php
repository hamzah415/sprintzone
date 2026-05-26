@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl md:text-3xl font-black italic uppercase tracking-tight">
            LAPORAN <span class="text-orange-500">STOK</span>
        </h2>
        
        <a href="{{ route('laporan.stok.export') }}" 
            class="px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-black uppercase">
            <i class="fas fa-download mr-1"></i> EXPORT CSV
        </a>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-blue-500 text-white p-4 rounded-lg">
            <p class="text-xs uppercase">Total Variant</p>
            <p class="text-2xl font-black">{{ $totalVariant }}</p>
        </div>
        
        <div class="bg-green-600 text-white p-4 rounded-lg">
            <p class="text-xs uppercase">Total Stok</p>
            <p class="text-2xl font-black">{{ $totalStock }}</p>
        </div>
        
        <div class="bg-yellow-500 text-white p-4 rounded-lg">
            <p class="text-xs uppercase">Low Stok</p>
            <p class="text-2xl font-black">{{ $lowStock->count() }}</p>
        </div>
        
        <div class="bg-red-500 text-white p-4 rounded-lg">
            <p class="text-xs uppercase">Habis</p>
            <p class="text-2xl font-black">{{ $outOfStock->count() }}</p>
        </div>
    </div>

    {{-- ALERT LOW STOCK --}}
    @if($lowStock->count() > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
        <h3 class="text-sm font-black uppercase text-yellow-700 mb-2">
            <i class="fas fa-exclamation-triangle mr-1"></i> Low Stock Warning
        </h3>
        <div class="flex flex-wrap gap-2">
            @foreach($lowStock as $item)
                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 text-xs rounded">
                    {{ $item->product->name ?? '-' }} ({{ $item->color ?? '' }} / {{ $item->size ?? '' }}) - {{ $item->stock }} pcs
                </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ALERT OUT OF STOCK --}}
    @if($outOfStock->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
        <h3 class="text-sm font-black uppercase text-red-700 mb-2">
            <i class="fas fa-exclamation-circle mr-1"></i> Out of Stock
        </h3>
        <div class="flex flex-wrap gap-2">
            @foreach($outOfStock as $item)
                <span class="bg-red-100 text-red-700 px-2 py-1 text-xs rounded">
                    {{ $item->product->name ?? '-' }} ({{ $item->color ?? '' }} / {{ $item->size ?? '' }})
                </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- TABLE STOK --}}
    <div class="card">
        <div class="table-responsive">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-bold">#</th>
                        <th class="p-4 text-left text-xs font-bold">Produk</th>
                        <th class="p-4 text-left text-xs font-bold">Kategori</th>
                        <th class="p-4 text-left text-xs font-bold">Brand</th>
                        <th class="p-4 text-left text-xs font-bold">Variant</th>
                        <th class="p-4 text-left text-xs font-bold">SKU</th>
                        <th class="p-4 text-left text-xs font-bold">Harga</th>
                        <th class="p-4 text-left text-xs font-bold">Stok</th>
                        <th class="p-4 text-left text-xs font-bold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($variants as $index => $variant)
                        <tr class="border-t {{ $variant->stock == 0 ? 'bg-red-50' : ($variant->stock < 5 ? 'bg-yellow-50' : '') }}">
                            <td class="p-4">{{ $index + 1 }}</td>
                            <td class="p-4 font-bold">{{ $variant->product->name ?? '-' }}</td>
                            <td class="p-4 text-xs">{{ $variant->product->category->name ?? '-' }}</td>
                            <td class="p-4 text-xs">{{ $variant->product->brand->name ?? '-' }}</td>
                            <td class="p-4">
                                <span class="bg-gray-800 text-white px-2 py-1 text-xs rounded">
                                    {{ $variant->color ?? '-' }} / {{ $variant->size ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 text-xs font-mono">{{ $variant->sku ?? '-' }}</td>
                            <td class="p-4 font-bold">Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="font-black text-lg">{{ $variant->stock }}</span>
                            </td>
                            <td class="p-4">
                                @if($variant->stock == 0)
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-600 font-bold">HABIS</span>
                                @elseif($variant->stock < 5)
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-600 font-bold">LOW</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-600 font-bold">READY</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-400">
                                Tidak ada data stok
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
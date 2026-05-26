@extends('layouts.admin.app')

@section('title', 'Purchase History')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-10">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-black italic uppercase tracking-tighter">
                    Purchase History
                </h1>
                <p class="text-gray-500 mt-2">Semua order dari seluruh user.</p>
            </div>

            {{-- FILTER BUTTONS --}}
            <div class="flex gap-2">
                <a href="{{ route('purchase.history', ['filter' => 'today']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase 
                {{ $filter == 'today' ? 'bg-orange-500 text-white' : 'bg-gray-100' }}">
                    HARI INI
                </a>
                <a href="{{ route('purchase.history', ['filter' => 'week']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase 
                {{ $filter == 'week' ? 'bg-orange-500 text-white' : 'bg-gray-100' }}">
                    MINGGU INI
                </a>
                <a href="{{ route('purchase.history', ['filter' => 'month']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase 
                {{ $filter == 'month' ? 'bg-orange-500 text-white' : 'bg-gray-100' }}">
                    BULAN INI
                </a>
                <a href="{{ route('purchase.history', ['filter' => 'all']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase 
                {{ $filter == 'all' ? 'bg-orange-500 text-white' : 'bg-gray-100' }}">
                    SEMUA
                </a>
            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-500 text-white p-4 rounded-xl">
                <p class="text-xs uppercase">Total Order</p>
                <p class="text-2xl font-black">{{ $orders->count() }}</p>
            </div>
            <div class="bg-green-600 text-white p-4 rounded-xl">
                <p class="text-xs uppercase">Pendapatan</p>
                <p class="text-2xl font-black">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-orange-500 text-white p-4 rounded-xl">
                <p class="text-xs uppercase">Items Terjual</p>
                <p class="text-2xl font-black">{{ $orders->flatMap->items->sum('qty') }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm">
            <div x-data="{ open: false, order: null }">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-t">
                                <td class="px-6 py-4">
                                    {{ $order->customer_name }}
                                </td>
                                <td class="px-6 py-4 font-bold">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-black
                                    @if ($order->status == 'success') bg-green-100 text-green-600
                                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-600
                                    @else bg-gray-100 text-gray-600 @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button
                                        @click="
                                    open = true;
                                    order = {
                                        id: '{{ $order->id }}',
                                        customer: '{{ $order->customer_name }}',
                                        total: '{{ number_format($order->total_price, 0, ',', '.') }}',
                                        status: '{{ $order->status }}',
                                        items: [
                                            @foreach ($order->items as $item)
                                            {
                                                name: '{{ $item->variant->product->name ?? '-' }}',
                                                color: '{{ $item->variant->color ?? '' }}',
                                                size: '{{ $item->variant->size ?? '' }}',
                                                qty: '{{ $item->qty }}',
                                                subtotal: '{{ number_format($item->subtotal, 0, ',', '.') }}'
                                            }, @endforeach
                                        ]
                                    }
                                "
                                        class="bg-black text-white px-4 py-2 rounded-xl text-xs font-black uppercase hover:bg-orange-500">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">Tidak ada order</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- MODAL (SAMA) --}}
                <div x-show="open" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4">
                    <div @click.outside="open = false" class="bg-white rounded-3xl w-full max-w-2xl p-8">
                        <div class="flex justify-between mb-8">
                            <div>
                                <h2 class="text-3xl font-black italic uppercase">Order Detail</h2>
                                <p class="text-gray-500">Order #<span x-text="order.id"></span></p>
                            </div>
                            <button @click="open = false" class="text-2xl font-black text-gray-400">×</button>
                        </div>

                        <div class="mb-4">
                            <div class="text-xs uppercase text-gray-400 font-black mb-2">Customer</div>
                            <div class="text-xl font-black" x-text="order.customer"></div>
                        </div>

                        <form :action="'/purchase-history/' + order.id + '/status'" method="POST" class="mb-8">
                            @csrf @method('PUT')
                            <div class="text-xs uppercase text-gray-400 font-black mb-2">Status</div>
                            <select name="status" x-model="order.status" onchange="this.form.submit()"
                                class="w-full border rounded-lg px-3 py-2 font-bold">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="success">Success</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </form>

                        <div class="space-y-4">
                            <template x-for="item in order.items">
                                <div class="flex justify-between border-b pb-4">
                                    <div>
                                        <div class="font-black" x-text="item.name"></div>
                                        <div class="text-sm text-gray-500">
                                            <span x-text="item.color"></span> / <span x-text="item.size"></span> x <span
                                                x-text="item.qty"></span>
                                        </div>
                                    </div>
                                    <div class="font-black text-orange-500">Rp <span x-text="item.subtotal"></span></div>
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-between mt-8 pt-6 border-t">
                            <span class="text-xl font-black uppercase">Total</span>
                            <span class="text-3xl font-black italic text-orange-500">Rp <span
                                    x-text="order.total"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

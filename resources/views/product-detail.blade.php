<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SprintZone - Product Detail</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .active-thumb { border: 2px solid #f97316; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <header class="bg-[#D9D9D9] py-3 px-4 md:px-8 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <div class="bg-black p-1">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><path d="M13.49 5.42c1.2.82 1.53 2.44.71 3.64l-1.2 1.75c-.82 1.2-2.44 1.53-3.64.71-1.2-.82-1.53-2.44-.71-3.64l1.2-1.75c.82-1.2 2.44-1.53 3.64-.71zM22 13.5h-4l-2 4 1.5 5h-3l-1-4-3-1-1 4h-3l1.5-6.5L4 12.5v-3l4-1 2-3h3l1 3 4 1.5 2 2.5h2v2z"/></svg>
            </div>
            <h1 class="text-xl md:text-2xl font-black italic tracking-tighter">SprintZone</h1>
        </div>

        <div class="hidden md:block flex-1 max-w-xl mx-10">
            <div class="relative">
                <input type="text" placeholder="Cari sepatu impianmu..." class="w-full py-2 px-10 rounded-lg border-none focus:ring-2 focus:ring-orange-400 outline-none shadow-sm">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="relative cursor-pointer hover:scale-110 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="flex items-center gap-2 cursor-pointer border-l border-gray-400 pl-6">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm">K</div>
                <span class="font-bold text-sm hidden md:block">Kiki Yusi</span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-6 md:py-10 grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ quantity: 1, price: 539000, variant: 'Red Edition' }">
        
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 p-4">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2070" alt="Sepatu" class="w-full h-auto transform hover:scale-105 transition duration-500">
            </div>
            <div class="flex gap-3 overflow-x-auto hide-scrollbar">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2070" class="w-20 h-20 rounded-lg active-thumb cursor-pointer object-cover">
                <div class="w-20 h-20 rounded-lg bg-gray-200 cursor-pointer"></div>
                <div class="w-20 h-20 rounded-lg bg-gray-200 cursor-pointer"></div>
                <div class="w-20 h-20 rounded-lg bg-gray-200 cursor-pointer"></div>
            </div>
        </div>

        <div class="lg:col-span-5 space-y-6">
            <div>
                <h2 class="text-2xl font-black italic uppercase tracking-tight leading-tight">SprintZone M6A Pro Max - Ultra Lightweight <br> <span class="text-orange-600 font-black">Performance Sneakers</span></h2>
                <div class="flex items-center gap-4 mt-2">
                    <span class="text-sm text-gray-500 font-medium">Terjual <span class="text-black">3 rb+</span></span>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-sm font-bold">4.9 <span class="text-gray-400 font-normal">(1.267 rating)</span></span>
                    </div>
                </div>
            </div>

            <div class="text-3xl font-black text-black">Rp539.000</div>

            <div class="space-y-3">
                <span class="text-sm font-bold text-gray-700">Pilih warna: <span class="text-gray-400 font-medium" x-text="variant"></span></span>
                <div class="flex gap-3">
                    <button @click="variant = 'Red Edition'" :class="variant === 'Red Edition' ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-200'" class="px-4 py-2 border rounded-full text-xs font-bold transition">Red Edition</button>
                    <button @click="variant = 'Dark Shadow'" :class="variant === 'Dark Shadow' ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-200'" class="px-4 py-2 border rounded-full text-xs font-bold transition">Dark Shadow</button>
                </div>
            </div>

            <div class="border-b border-gray-200">
                <nav class="flex gap-8">
                    <button class="border-b-2 border-orange-500 pb-2 text-sm font-black text-orange-600 italic">Detail Produk</button>
                    <button class="pb-2 text-sm font-bold text-gray-400 hover:text-black">Spesifikasi</button>
                    <button class="pb-2 text-sm font-bold text-gray-400 hover:text-black">Info Penting</button>
                </nav>
            </div>

            <div class="text-sm text-gray-600 leading-relaxed">
                <p>Kondisi: <span class="font-bold text-black">Baru</span></p>
                <p>Berat Satuan: <span class="font-bold text-black">400 g</span></p>
                <p class="mt-4">Nikmati kenyamanan maksimal dengan teknologi PowerSteps. Didesain untuk mendukung gerakan dinamis Anda setiap hari.</p>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-5 sticky top-24 space-y-6">
                <h4 class="font-black italic uppercase text-sm tracking-tight">Atur jumlah dan catatan</h4>
                
                <div class="flex items-center gap-4">
                    <div class="flex items-center border border-gray-300 rounded-lg p-1">
                        <button @click="quantity > 1 ? quantity-- : null" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-500 font-bold">-</button>
                        <input type="number" x-model="quantity" class="w-10 text-center border-none focus:ring-0 font-bold text-sm bg-transparent">
                        <button @click="quantity++" class="w-8 h-8 flex items-center justify-center text-orange-500 font-bold">+</button>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Stok: <span class="text-black font-bold">26</span></span>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <span class="text-sm text-gray-500 font-medium">Subtotal</span>
                    <span class="text-xl font-black italic tracking-tight" x-text="'Rp' + (price * quantity).toLocaleString('id-ID')"></span>
                </div>

                <div class="space-y-2">
                    <button class="w-full bg-orange-500 text-white font-black italic uppercase py-3 rounded-xl shadow-lg shadow-orange-200 hover:bg-orange-600 transition active:scale-95">
                        + Keranjang
                    </button>
                    <button class="w-full border-2 border-black text-black font-black italic uppercase py-3 rounded-xl hover:bg-black hover:text-white transition active:scale-95">
                        Beli Langsung
                    </button>
                </div>

                <div class="flex justify-around items-center pt-4 border-t border-gray-100">
                    <button class="flex items-center gap-1 text-xs font-bold text-gray-500 hover:text-black">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Chat
                    </button>
                    <button class="flex items-center gap-1 text-xs font-bold text-gray-500 hover:text-black">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Wishlist
                    </button>
                    <button class="flex items-center gap-1 text-xs font-bold text-gray-500 hover:text-black">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        Share
                    </button>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
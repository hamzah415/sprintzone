<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>SprintZone - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">

    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (typeof window.performance != "" && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>

    <style>
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .side-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
            display: none !important;
            visibility: hidden;
        }

        .dropdown-submenu:hover>.side-menu {
            display: block !important;
            visibility: visible;
            opacity: 1;
        }

        .dropdown-menu {
            overflow: visible !important;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col overflow-x-hidden">

    {{-- TOAST NOTIFICATION --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
            class="fixed top-5 right-5 z-[9999]">
            <div
                class="bg-black text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-orange-500">
                <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">✓
                </div>
                <div>
                    <div class="text-xs font-black uppercase tracking-widest text-orange-400">Success</div>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="sticky top-0 z-50">
        <header x-data="{ loginOpen: false }"
            class="bg-[#D9D9D9] py-3 px-4 md:px-8 flex items-center justify-between shadow-sm">

            {{-- LOGO --}}
            <div class="flex items-center gap-1">
                <img src="{{ asset('img/logo.png') }}" alt="SprintZone Logo"
                    class="h-8 md:h-10 w-auto object-contain brightness-0 invert"
                    style="filter: drop-shadow(0 0 1px white);">
                <h1 class="text-xl md:text-2xl font-black italic tracking-tighter -ml-1">SprintZone</h1>
            </div>

            {{-- USER INFO & LOGOUT --}}
            <div class="flex items-center gap-4 md:gap-6">
                @auth
                    <div class="flex items-center gap-3">
                        <div class="text-sm font-bold uppercase italic tracking-tighter">
                            {{ Auth::user()->name }}
                        </div>
                        <a href="/" target="_blank"
                            class="text-[10px] font-black text-orange-500 uppercase border border-orange-500 px-2 py-1 rounded hover:bg-orange-500 hover:text-white transition decoration-none">
                            Lihat Toko
                        </a>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="text-[10px] font-black text-red-500 uppercase border border-red-500 px-2 py-1 rounded hover:bg-red-500 hover:text-white transition decoration-none">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                @endauth
            </div>
        </header>

        {{-- ADMIN NAVIGATION --}}
        <nav class="bg-[#E5E5E5] border-b border-gray-300">
            <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-3">
                <div class="flex gap-6 md:gap-12 text-xs font-bold text-gray-600 uppercase tracking-tighter">

                    {{-- SETTINGS --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 hover:text-orange-500 transition outline-none"
                            :class="open ? 'text-orange-500' : ''">
                            SETTINGS <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute left-0 mt-3 w-48 bg-white border border-gray-200 shadow-xl rounded-lg py-2 z-[100]">
                            <a href="{{ route('companies.index') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">Company</a>
                            <a href="{{ route('admin.users.index') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">User
                                Management</a>
                        </div>
                    </div>

                    {{-- MASTER DATA --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 hover:text-orange-500 transition outline-none"
                            :class="open ? 'text-orange-500' : ''">
                            MASTER DATA <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute left-0 mt-3 w-48 bg-white border border-gray-200 shadow-xl rounded-lg py-2 z-[100]">
                            <div class="dropdown-submenu">
                                <a
                                    class="flex items-center justify-between px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition cursor-pointer decoration-none text-gray-600">
                                    PRODUCT <svg class="w-2 h-2 -rotate-90" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>
                                <div
                                    class="side-menu absolute left-full top-0 ml-0 w-40 bg-white border border-gray-200 shadow-xl rounded-lg py-2">
                                    <a href="{{ route('categories.index') }}"
                                        class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition decoration-none text-gray-600">Category</a>
                                    <a href="{{ route('brands.index') }}"
                                        class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition decoration-none text-gray-600">Brand</a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="/products"
                                        class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition decoration-none text-gray-600">Product
                                        List</a>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 my-1"></div>
                            <div class="dropdown-submenu">
                                <a
                                    class="flex items-center justify-between px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition cursor-pointer decoration-none text-gray-600">
                                    PARTNERS <svg class="w-2 h-2 -rotate-90" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>
                                <div
                                    class="side-menu absolute left-full top-0 ml-0 w-40 bg-white border border-gray-200 shadow-xl rounded-lg py-2">
                                    <a href="{{ route('companies.index') }}"
                                        class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition decoration-none text-gray-600">Customer/Vendor</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TRANSACTION --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 hover:text-orange-500 transition outline-none"
                            :class="open ? 'text-orange-500' : ''">
                            TRANSACTION <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute left-0 mt-3 w-48 bg-white border border-gray-200 shadow-xl rounded-lg py-2 z-[100]">
                            <a href="#"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">Sales</a>
                            <a href="#"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">Purchase</a>
                        </div>
                    </div>

                    {{-- REPORTING --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 hover:text-orange-500 transition outline-none"
                            :class="open ? 'text-orange-500' : ''">
                            REPORTING <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute left-0 mt-3 w-56 bg-white border border-gray-200 shadow-xl rounded-lg py-2 z-[100]">

                            {{-- DASHBOARD --}}
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">
                                <i class="fas fa-home w-6"></i> Dashboard
                            </a>

                            <div class="border-t border-gray-100 my-1"></div>

                            {{-- LAPORAN PENJUALAN --}}
                            <a href="{{ route('laporan.penjualan') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">
                                <i class="fas fa-chart-line w-6"></i> Laporan Penjualan
                            </a>

                            {{-- LAPORAN STOK --}}
                            <a href="{{ route('laporan.stok') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">
                                <i class="fas fa-box w-6"></i> Laporan Stok
                            </a>

                            {{-- LAPORAN BRAND --}}
                            <a href="{{ route('laporan.brand-kategori') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">
                                <i class="fas fa-tags w-6"></i> Laporan Brand
                            </a>

                            <div class="border-t border-gray-100 my-1"></div>

                            {{-- USER MANAGEMENT --}}
                            <a href="{{ route('laporan.user') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">
                                <i class="fas fa-users w-6"></i> User Report
                            </a>

                            {{-- PURCHASE HISTORY (MANAGE ORDER) --}}
                            <a href="{{ route('purchase.history') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">
                                <i class="fas fa-shopping-cart w-6"></i> Purchase History
                            </a>

                        </div>
                    </div>

                    <a href="{{ route('manual') }}" class="hover:text-orange-500 transition decoration-none">
                        MANUAL BOOK
                    </a>

                </div>
            </div>
        </nav>
    </div>

    <main class="flex-grow">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

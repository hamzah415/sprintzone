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
        }

        .dropdown-menu {
            overflow: visible !important;
        }

        [x-cloak] {
            display: none !important;
        }

        nav a.no-underline:hover {
            color: #FF7B1B !important;
            text-decoration: none !important;
        }

        .sticky.top-0 {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        @yield('styles');
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

    <div class="sticky top-0 z-50 shadow-sm">
        <header x-data="{ loginOpen: false }"
            class="bg-[#D9D9D9] py-3 px-4 md:px-8 flex items-center justify-between border-b border-gray-300/20">

            {{-- LOGO --}}
            <div class="flex items-center gap-1">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('img/logo.png') }}" alt="SprintZone Logo"
                        class="h-8 md:h-10 w-auto object-contain brightness-0 invert"
                        style="filter: drop-shadow(0 0 1px white);">
                </div>
                <h1 class="text-xl md:text-2xl font-black italic tracking-tighter -ml-1">SprintZone</h1>
            </div>

            {{-- SEARCH POPUP --}}
            <form action="{{ route('search') }}" method="GET" class="hidden md:block flex-1 max-w-xl mx-10 relative"
                x-data="{
                    q: '',
                    results: [],
                    loading: false,
                    async search() {
                        if (this.q.length < 2) {
                            this.results = [];
                            return;
                        }
                        this.loading = true;
                        try {
                            const res = await fetch('/api/search?q=' + encodeURIComponent(this.q));
                            this.results = await res.json();
                        } catch (e) {}
                        this.loading = false;
                    }
                }">
                <div class="relative">
                    <input type="text" x-model="q" @input.debounce.300ms="search()" name="q"
                        placeholder="Search produk atau brand..."
                        class="w-full py-2 px-10 rounded-lg border-none focus:ring-2 focus:ring-orange-400 outline-none shadow-sm"
                        autocomplete="off">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                {{-- POPUP HASIL --}}
                <div x-show="q.length >= 2" x-cloak
                    class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border max-h-80 overflow-y-auto z-50"
                    @click.outside="q = ''">

                    <div x-show="loading" class="p-4 text-center text-gray-400">Mencari...</div>

                    <div x-show="!loading && results.length === 0 && q.length >= 2"
                        class="p-4 text-center text-gray-400">
                        Produk tidak ditemukan
                    </div>

                    <div x-show="!loading && results.length > 0">
                        <template x-for="product in results" :key="product.id">
                            <a :href="'/etalase/' + product.id"
                                class="flex items-center gap-3 p-3 hover:bg-orange-50 border-b no-underline text-dark">
                                <img :src="product.image ? '/storage/' + product.image : '/img/no-image.png'"
                                    class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                                <div class="flex-1">
                                    <div class="text-sm font-bold text-gray-800" x-text="product.name"></div>
                                    <div class="text-xs text-orange-500" x-text="product.brand_name"></div>
                                </div>
                                <div class="text-sm font-bold text-orange-600"
                                    x-text="'Rp ' + formatRupiah(product.price)"></div>
                            </a>
                        </template>

                        <div class="p-3 border-t bg-gray-50">
                            <a :href="'/search?q=' + q"
                                class="w-full block text-center text-sm text-orange-500 font-bold hover:underline">
                                Lihat semua hasil →
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- CART & USER --}}
            <div class="relative flex items-center gap-4 md:gap-6">
                <div class="flex gap-4 items-center">
                    <div class="relative cursor-pointer hover:scale-110 transition">
                        @php
                            $cartCount = 0;
                            if (auth()->check()) {
                                $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('qty');
                            }
                        @endphp

                        @auth
                            <a href="{{ route('cart.index') }}"
                                class="relative flex items-center justify-center w-11 h-11 rounded-full text-gray-700 hover:bg-gray-100 hover:text-black transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                @if ($cartCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 flex items-center justify-center rounded-full bg-orange-500 text-white text-[10px] font-bold">{{ $cartCount }}</span>
                                @endif
                            </a>
                        @else
                            <div class="relative flex items-center justify-center w-11 h-11 rounded-full text-gray-400 opacity-50"
                                title="Login untuk melihat keranjang">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        @endauth
                    </div>
                </div>

                <div class="h-6 w-[1px] bg-gray-400 hidden md:block"></div>

                @guest
                    <div x-data="{ loginOpen: false }" class="relative">
                        <button @click="loginOpen = !loginOpen" type="button"
                            class="font-bold text-sm flex items-center gap-1 hover:text-orange-600 transition">
                            Login
                            <svg class="w-4 h-4 transition-transform duration-300" :class="loginOpen ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="loginOpen" x-cloak x-transition @click.outside="loginOpen = false"
                            class="absolute right-0 top-12 w-72 bg-white rounded-2xl shadow-xl border p-6 z-[60]"
                            @click.stop>
                            <h3 class="text-lg font-black italic mb-4 uppercase">Sign In</h3>

                            <form action="{{ route('login.store') }}" method="POST" class="space-y-3" @submit.stop>
                                @csrf
                                <input type="email" name="email"
                                    class="w-full bg-gray-50 border rounded-lg py-2 px-3 text-xs outline-none focus:ring-1 focus:ring-orange-400"
                                    placeholder="Email" required>
                                <input type="password" name="password"
                                    class="w-full bg-gray-50 border rounded-lg py-2 px-3 text-xs outline-none focus:ring-1 focus:ring-orange-400"
                                    placeholder="Password" required>
                                <button type="submit"
                                    class="w-full bg-black text-white text-[10px] font-black py-2.5 rounded-lg uppercase tracking-widest hover:bg-orange-600 transition">Login</button>
                            </form>

                            <div class="py-3 flex items-center">
                                <div class="flex-grow border-t"></div>
                                <span class="mx-2 text-[9px] text-gray-300 font-bold">OR</span>
                                <div class="flex-grow border-t"></div>
                            </div>

                            <a href="{{ route('google.login') }}"
                                class="w-full flex items-center justify-center gap-2 border rounded-lg py-2 text-[10px] font-bold hover:bg-gray-50 transition no-underline text-dark mb-3">
                                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                                    class="w-4 h-4 mr-1"> Google Login
                            </a>

                            <p class="text-center text-[10px] text-gray-500 font-bold">
                                Don't have an account?
                                <a href="{{ route('uregister') }}" class="text-orange-500 hover:underline">Sign
                                    Up</a><br><br>
                                Trouble with 2FA?
                                <a href="{{ route('2fa.recovery') }}" class="text-orange-500 hover:underline">Recovery</a>
                            </p>
                        </div>
                    </div>
                @endguest

                @auth
                    <div class="flex items-center gap-3">
                        <div x-data="{ userDropdown: false }" class="relative flex items-center gap-3">

                            {{-- Avatar Button --}}
                            <button @click="userDropdown = !userDropdown" type="button"
                                class="flex items-center gap-2 group">

                                {{-- Foto Profil / Inisial --}}
                                <div
                                    class="relative w-10 h-10 rounded-full overflow-hidden border-2 border-transparent group-hover:border-orange-500 transition-all">
                                    @if (Auth::user()->image)
                                        <img src="{{ asset('storage/' . Auth::user()->image) }}"
                                            alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full bg-orange-500 flex items-center justify-center text-white font-black text-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Nama + Panah --}}
                                <span
                                    class="text-sm font-bold uppercase italic tracking-tighter text-gray-700 group-hover:text-orange-600 transition hidden md:block">
                                    Hi, {{ explode(' ', Auth::user()->name)[0] }}
                                    <svg class="w-4 h-4 transition-transform duration-300 inline"
                                        :class="userDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="userDropdown" x-cloak x-transition @click.outside="userDropdown = false"
                                class="absolute right-0 top-14 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 z-[60]">

                                {{-- User Info Header --}}
                                <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-3">

                                    {{-- Foto Besar --}}
                                    <div class="relative w-14 h-14 rounded-full overflow-hidden ring-4 ring-orange-100">
                                        @if (Auth::user()->image)
                                            <img src="{{ asset('storage/' . Auth::user()->image) }}"
                                                alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full bg-orange-500 flex items-center justify-center text-white font-black text-xl">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="text-base font-bold text-gray-800 truncate">
                                            {{ Auth::user()->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 truncate">
                                            {{ Auth::user()->email }}
                                        </div>
                                        @if (Auth::user()->phone)
                                            <div class="text-xs text-gray-400 truncate">
                                                {{ Auth::user()->phone }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Menu Items --}}
                                <div class="space-y-1">
                                    <a href="{{ route('myorder.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition no-underline">
                                        <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        My Order
                                        <span
                                            class="ml-auto bg-orange-100 text-orange-600 text-xs px-2 py-0.5 rounded-full font-bold">
                                            {{ \App\Models\Order::where('user_id', Auth::id())->where('status', '!=', 'delivered')->count() }}
                                        </span>
                                    </a>

                                    <a href="{{ route('profile.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition no-underline">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        Profile
                                    </a>

                                    @if (Auth::user()->role === 'admin')
                                        <div class="border-t border-gray-100 my-2"></div>

                                        <a href="{{ route('dashboard') }}"
                                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-black hover:text-white rounded-xl transition no-underline">
                                            <div class="w-8 h-8 rounded-lg bg-black flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                                </svg>
                                            </div>
                                            Dashboard
                                        </a>
                                    @endif

                                    <div class="border-t border-gray-100 my-2"></div>

                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition">
                                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                            </div>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth

            </div>
        </header>

        <nav class="bg-[#E5E5E5] border-b border-gray-300">
            <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-3">
                <div class="flex gap-6 md:gap-12 text-xs font-bold text-gray-600 uppercase tracking-tighter">
                    <a href="{{ route('welcome') }}"
                        class="hover:text-orange-500 transition no-underline text-dark">Home</a>
                    <a href="{{ route('products.etalase') }}"
                        class="hover:text-orange-500 transition no-underline text-dark">Sprint Shop</a>
                    <a href="{{ route('brand') }}"
                        class="hover:text-orange-500 transition no-underline text-dark">Brands</a>
                    <a href="#" class="hover:text-orange-500 transition no-underline text-dark">Tanya Zone</a>
                </div>
            </div>
        </nav>
    </div>

    <main class="flex-grow">
        @yield('content')
    </main>

    <script>
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js

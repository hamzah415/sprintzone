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
            if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation
                    .type === 2)) {
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

    <div class="sticky top-0 z-50">
        <header x-data="{ loginOpen: false }"
            class="bg-[#D9D9D9] py-3 px-4 md:px-8 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-1">
                <img src="{{ asset('img/logo.png') }}" alt="SprintZone Logo"
                    class="h-8 md:h-10 w-auto object-contain brightness-0 invert"
                    style="filter: drop-shadow(0 0 1px white);">
                <h1 class="text-xl md:text-2xl font-black italic tracking-tighter -ml-1">SprintZone</h1>
            </div>

            <div class="hidden md:block flex-1 max-w-xl mx-10">
                <div class="relative">
                    <input type="text" placeholder="Search..."
                        class="w-full py-2 px-10 rounded-lg border-none focus:ring-2 focus:ring-orange-400 outline-none shadow-sm text-sm">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center gap-4 md:gap-6">
                @guest
                    <div class="relative">
                        <button @click="loginOpen = !loginOpen"
                            class="font-bold text-sm flex items-center gap-1 hover:text-orange-600 transition outline-none">
                            Login <svg class="w-4 h-4 transition-transform duration-300"
                                :class="loginOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="loginOpen" x-cloak @click.outside="loginOpen = false" x-transition
                            class="absolute right-0 top-10 w-72 bg-white rounded-2xl shadow-xl border p-6 z-[60]">
                            <h3 class="text-lg font-black italic mb-4 uppercase">Sign In</h3>
                            <form action="{{ route('login') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="email" name="email"
                                    class="w-full bg-gray-50 border rounded-lg py-2 px-3 text-xs outline-none focus:ring-1 focus:ring-orange-400"
                                    placeholder="Email" required>
                                <input type="password" name="password"
                                    class="w-full bg-gray-50 border rounded-lg py-2 px-3 text-xs outline-none focus:ring-1 focus:ring-orange-400"
                                    placeholder="Password" required>
                                <button
                                    class="w-full bg-black text-white text-[10px] font-black py-2.5 rounded-lg uppercase tracking-widest hover:bg-orange-600 transition">Login</button>
                            </form>
                        </div>
                    </div>
                @endguest

                @auth
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold uppercase italic tracking-tighter hidden md:inline">Hi,
                            {{ Auth::user()->name }}</span>
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

        <nav class="bg-[#E5E5E5] border-b border-gray-300">
            <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-3">
                <div class="flex gap-6 md:gap-12 text-xs font-bold text-gray-600 uppercase tracking-tighter">

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

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 hover:text-orange-500 transition outline-none">
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
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">
                                Purchase 
                            </a>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 hover:text-orange-500 transition outline-none">
                            REPORTING <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute left-0 mt-3 w-48 bg-white border border-gray-200 shadow-xl rounded-lg py-2 z-[100]">
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">Dashboard</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <div class="dropdown-submenu">
                                <a
                                    class="flex items-center justify-between px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition cursor-pointer decoration-none text-gray-600">
                                    HISTORY <svg class="w-2 h-2 -rotate-90" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>
                                <div
                                    class="side-menu absolute left-full top-0 ml-0 w-40 bg-white border border-gray-200 shadow-xl rounded-lg py-2">
                                    <a href="#"
                                        class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">Sales
                                        History</a>
                                    <a href="{{ route('purchase.history') }}"
                                        class="block px-4 py-2 hover:bg-gray-50 hover:text-orange-500 transition">Purchase
                                        History</a>
                                </div>
                            </div>
                        </div>
                    </div>
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

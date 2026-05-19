<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SprintZone - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-sprint {
            background-color: #D9D9D9;
        }
    </style>
</head>

<body>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">

        <div class="w-full max-w-md">

            {{-- LOGO --}}
            <div class="text-center mb-8">

                <h1 class="text-4xl font-black italic uppercase tracking-tight">
                    Sprint<span class="text-orange-500">Zone</span>
                </h1>

                <p class="text-sm text-gray-500 mt-2">
                    Welcome back
                </p>

            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <form method="POST" action="/login" class="space-y-5">
                        @csrf

                        {{-- EMAIL --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>

                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="email@example.com"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">

                            @error('email')
                                <p class="text-red-500 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- PASSWORD --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>

                            <input type="password" name="password" required placeholder="Enter your password"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">

                            @error('password')
                                <p class="text-red-500 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- REMEMBER --}}
                        <div class="flex items-center justify-between text-sm">

                            <label class="flex items-center gap-2 text-gray-600">

                                <input type="checkbox" name="remember"
                                    class="rounded border-gray-300 text-orange-500 focus:ring-orange-400">

                                Remember me

                            </label>

                        </div>

                        {{-- BUTTON --}}
                        <button type="submit"
                            class="w-full bg-black text-white font-black py-4 rounded-xl uppercase tracking-widest hover:bg-orange-600 transition-all transform hover:scale-[1.02] shadow-lg">

                            Login

                        </button>

                    </form>


                    {{-- REGISTER --}}
                    <p class="text-center text-sm text-gray-500 mt-6">

                        Don’t have an account?

                        <a href="{{ route('uregister') }}" class="text-orange-500 font-semibold hover:text-orange-600">

                            Create Account

                        </a>

                    </p>

                </div>
            </div>

            {{-- FOOTER --}}
            <p class="text-center mt-8 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                &copy; 2026 SprintZone. All Rights Reserved.
            </p>

        </div>

    </div>

</body>

</html>

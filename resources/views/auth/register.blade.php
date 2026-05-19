<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SprintZone - Join the Movement</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-sprint { background-color: #D9D9D9; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black italic tracking-tighter uppercase">
                Sprint<span class="text-orange-600">Zone</span>
            </h1>
            <p class="text-gray-500 text-sm font-bold tracking-widest uppercase mt-1">Create your account</p>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('uregister.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1 ml-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm outline-none focus:ring-2 focus:ring-orange-400 transition" 
                            placeholder="Enter your name" required>
                        @error('name') <span class="text-red-500 text-[10px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1 ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm outline-none focus:ring-2 focus:ring-orange-400 transition" 
                            placeholder="email@example.com" required>
                        @error('email') <span class="text-red-500 text-[10px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1 ml-1">Password</label>
                            <input type="password" name="password" 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm outline-none focus:ring-2 focus:ring-orange-400 transition" 
                                placeholder="Enter Password" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1 ml-1">Confirm</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm outline-none focus:ring-2 focus:ring-orange-400 transition" 
                                placeholder="Confirm Password" required>
                        </div>
                        @error('password') <div class="col-span-2 text-red-500 text-[10px] mt-1">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black text-white font-black py-4 rounded-xl uppercase tracking-widest hover:bg-orange-600 transition-all transform hover:scale-[1.02] shadow-lg">
                        Create Account
                    </button>
                </form>

                <div class="py-6 flex items-center">
                    <div class="flex-grow border-t border-gray-100"></div>
                    <span class="mx-4 text-[10px] text-gray-300 font-bold">ALREADY HAVE AN ACCOUNT?</span>
                    <div class="flex-grow border-t border-gray-100"></div>
                </div>

                <a href="{{ route('welcome') }}" class="w-full flex items-center justify-center border-2 border-black rounded-xl py-3 text-[11px] font-black uppercase tracking-widest hover:bg-gray-50 transition no-underline text-black">
                    Sign In Here
                </a>
            </div>
        </div>

        <p class="text-center mt-8 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
            &copy; 2026 SprintZone. All Rights Reserved.
        </p>
    </div>

</body>
</html>
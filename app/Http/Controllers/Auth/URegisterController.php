<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class URegisterController extends Controller
{
    /**
     * Menampilkan view registrasi
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Menangani proses registrasi user baru
     */
    public function register(Request $request)
    {
        // 1. Validasi Input (disesuaikan dengan batasan tabel)
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Pembuatan User (Mengikuti struktur kolom di gambar)
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            
            // Mengisi field 'role' secara eksplisit sebagai 'user'
            'role'              => 'user', 
            
            // Kolom nullable lainnya dibiarkan NULL sesuai struktur tabel
            'google_id'         => null,
            'google2fa_secret'  => null,
            'remember_token'    => Str::random(10),
        ]);

        // 3. Otomatis Login setelah berhasil daftar
        Auth::login($user);

        // 4. Redirect ke halaman tujuan
        return redirect()->route('welcome')->with('success', 'Registrasi berhasil! Selamat datang di SprintZone.');
    }
}
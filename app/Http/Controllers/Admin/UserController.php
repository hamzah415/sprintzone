<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user
     */
    public function index()
    {
        $users = User::all();
	$companies = Company::all();
	
        // Mengarahkan ke file resources/views/admin/users/index.blade.php
        return view('admin.users.index', compact('users', 'companies'));
    }

    /**
     * Menyimpan user baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password wajib di-hash!
            'role' => $request->role,
        ]);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Memperbarui data user yang sudah ada
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
		'company_id' => 'nullable|exists:companies,id',
        ]);

        // Jika password diisi (ingin ganti password), maka update passwordnya
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->role = $request->role;
	$user->company_id = $request->company_id;
        $user->save();

        return back()->with('success', 'Data user berhasil diperbarui!');
    }
}
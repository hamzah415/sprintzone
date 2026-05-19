<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Tampilkan halaman profile
    public function index()
    {
        return view('profile.index');
    }
    
    // Update profile
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }
        
        if ($request->filled('address')) {
            $user->address = $request->address;
        }
        
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
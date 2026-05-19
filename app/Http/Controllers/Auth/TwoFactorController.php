<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFactorController extends Controller
{
    /**
     * Menampilkan QR Code tanpa status Login
     */
    public function showSetupForm(Request $request)
    {
        // Ambil ID user dari session sementara (dari GoogleController)
        $userId = session('2fa_temp_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::findOrFail($userId);
        $google2fa = app('pragmarx.google2fa');

        // Jika belum ada secret, buatkan
        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        return view('auth.2fa_setup', [
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $user->google2fa_secret
        ]);
    }

    /**
     * Membatalkan setup dan membersihkan session sementara
     */
    public function cancel2fa(Request $request)
    {
        $userId = session('2fa_temp_user_id');
        
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $user->update(['google2fa_secret' => null, 'has_2fa' => 0]);
            }
        }

        // Hapus session sementara
        $request->session()->forget('2fa_temp_user_id');

        return redirect('/')->with('info', 'Setup 2FA dibatalkan.');
    }

    /**
     * Menyelesaikan Setup dan RESMI LOGIN
     */
    public function finishSetup(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric',
        ]);

        $userId = session('2fa_temp_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::findOrFail($userId);
        $google2fa = app('pragmarx.google2fa');

        // Verifikasi kode OTP
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            // 1. Update flag has_2fa menjadi 1
            $user->update(['has_2fa' => 1]);

            // 2. RESMI LOGIN DI SINI
            Auth::login($user);
            
            // 3. Tandai authenticator sebagai terverifikasi
            app(Authenticator::class)->boot($request)->login();

            // 4. Hapus session sementara
            $request->session()->forget('2fa_temp_user_id');

            return $this->redirectByRole($user);
        }

        return back()->withErrors(['one_time_password' => 'Kode OTP salah, silakan coba lagi.']);
    }

    public function showVerifyForm()
    {
        if (!session()->has('2fa_temp_user_id')) return redirect()->route('login');
        return view('auth.2fa_verify');
    }

    /**
     * Verifikasi OTP untuk user yang SUDAH punya flag has_2fa = 1
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric',
        ]);

        $userId = session('2fa_temp_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::findOrFail($userId);
        $google2fa = app('pragmarx.google2fa');

        if ($google2fa->verifyKey($user->google2fa_secret, $request->one_time_password)) {
            
            // RESMI LOGIN DI SINI
            Auth::login($user);
            app(Authenticator::class)->boot($request)->login();
            
            $request->session()->forget('2fa_temp_user_id');

            return $this->redirectByRole($user);
        }

        return back()->withErrors(['one_time_password' => 'Kode OTP tidak valid.']);
    }

    /**
     * Helper untuk redirect
     */
    private function redirectByRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('products.etalase');
    }
}
<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            $finduser = User::where('google_id', $user->id)
                            ->orWhere('email', $user->email)
                            ->first();
      
            if($finduser){
                if(!$finduser->google_id) {
                    $finduser->update(['google_id' => $user->id]);
                }

                session(['2fa_temp_user_id' => $finduser->id]);
		session()->save();

                if ($finduser->has_2fa == 1) {
                    return redirect()->route('2fa.verify');
                }
                
                return redirect()->route('2fa.setup');

            } else {
                // Untuk user baru, biasanya langsung login atau paksa setup juga
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => null
                ]);
     
                // Opsional: Jika user baru juga wajib 2FA, gunakan temp session
                session(['2fa_temp_user_id' => $newUser->id]);
                return redirect()->route('2fa.setup');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Gagal login dengan Google.');
        }
    }

    /**
     * Membatalkan setup 2FA
     */
    public function cancel2fa(Request $request)
    {
        // Karena kita belum login resmi (Auth::user() pasti null), 
        // kita ambil user dari session sementara
        $userId = session('2fa_temp_user_id');

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                // Bersihkan data secret karena batal setup
                $user->update([
                    'google2fa_secret' => null,
                    'has_2fa' => 0
                ]);
            }
        }

        // Hapus session sementara agar benar-benar bersih
        $request->session()->forget('2fa_temp_user_id');
        
        // Pastikan logout jika entah bagaimana ada session login yang tersangkut
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
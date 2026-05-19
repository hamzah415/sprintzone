<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RecoveryController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
	->scopes(['openid', 'profile', 'email'])
	->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google2fa_secret' => null,
                    'has_2fa' => 0,
                ]);
		$user->save();	
	
		Auth::login($user);
		session()->forget('google2fa');

                return redirect()->route('2fa.setup'); 
            }

            return redirect('/')->with('error', 'Email tidak terdaftar.');

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Gagal melakukan recovery.');
        }
    }
}
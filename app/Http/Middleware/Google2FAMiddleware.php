<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Google2FAMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($request->is('2fa/verify*') || $request->is('2fa/setup*')) {
                return $this->redirectByRole($user);
            }

            return $next($request);
        }

        if ($request->session()->has('2fa_temp_user_id')) {
            if ($request->is('2fa/setup*') || $request->is('2fa/verify*') || $request->is('2fa/cancel*')) {
                return $next($request);
            }

            $user = User::find($request->session()->get('2fa_temp_user_id'));

		if ($user) {
                	return $user->has_2fa == 1 
                    	? redirect()->route('2fa.verify') 
                    	: redirect()->route('2fa.setup');
            	}
        }

        if ($request->is('2fa/verify*') || $request->is('2fa/setup*')) {
            return redirect()->route('welcome');
        }

        return $next($request);
    }

    /**
     * Helper untuk redirect berdasarkan Role
     */
    private function redirectByRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('products.etalase');
    }
}
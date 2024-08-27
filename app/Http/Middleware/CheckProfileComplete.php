<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CheckProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();


        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->first_name || !$user->last_name || !$user->username || Hash::check('12345678', $user->password)) {
                if (url()->current() != route('account.settings')) {
                    return redirect()->route('account.settings')->with('warning', 'Please complete your profile information and change your password.');
                }
            } 
        }

        return $next($request);
    }
}

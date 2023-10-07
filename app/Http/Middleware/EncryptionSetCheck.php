<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EncryptionSetCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        if(is_null($user->encryption_method) && is_null($user->encryption_mode)){
            return redirect('home')->with('alert', 'Please set your encryption method first.');
        }
        return $next($request);
    }
}

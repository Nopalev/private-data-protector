<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route()->parameters()['id'];
        $file = File::find($id);
        if(Auth::user()->id === $file->user_id){
            return $next($request);
        }
        return redirect()->back()->with('alert', 'You are unauthorized to access this file');
    }
}

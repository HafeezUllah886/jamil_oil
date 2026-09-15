<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ConfirmPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Check if user is authenticated
         Session::put('prev_url', url()->previous());
         Session::put('intended_url', url()->current());
             // Check if the user has confirmed their password
             if (!session('confirmed_password')) {
                 return redirect()->route('confirm-password'); // Change to your confirm password route
             }
 
         return $next($request);
    }
}

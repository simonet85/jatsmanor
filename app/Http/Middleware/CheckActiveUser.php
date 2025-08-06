<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Votre compte a été désactivé. Contactez l\'administrateur.'
                ], 403);
            }
            
            return redirect()->route('login')
                           ->withErrors(['email' => 'Votre compte a été désactivé. Contactez l\'administrateur.']);
        }
        
        return $next($request);
    }
}

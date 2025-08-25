<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses ditolak! Hanya admin yang bisa mengakses halaman ini.');
        }

        return $next($request);
    }
}

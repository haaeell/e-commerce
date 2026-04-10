<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'customer') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Customer access required.'], 403);
            }
            return redirect('/')->with('error', 'Akses ditolak. Halaman ini khusus customer.');
        }

        return $next($request);
    }
}

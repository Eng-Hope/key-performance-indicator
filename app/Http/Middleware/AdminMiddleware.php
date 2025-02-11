<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
     
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $userId = Auth::id();
    $isAdmin = Role::where('user_id', $userId)->where('role', 'admin')->exists();

    if (!$isAdmin) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    return $next($request);
    }
}

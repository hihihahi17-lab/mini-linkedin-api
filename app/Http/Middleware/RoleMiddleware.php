<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
       
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Non authentifié'
            ], 401);
        }

       
        if (!in_array(auth()->user()->role, $roles)) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        return $next($request);
    }
}
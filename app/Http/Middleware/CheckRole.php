<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = JWTAuth::parseToken()->authenticate();

        foreach ($roles as $role) {
            if ($user->role->nama_role == $role) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $jwt_key = config('app.jwt_key');
            $token = $request->header('Authorization');
            $decoded = JWT::decode($token, new Key($jwt_key, 'HS256'));
            app()->bind('user_id', function () use ($decoded) {
                return (int) $decoded->user_id;
            });
            return $next($request);
        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if(!$request->hasCookie('refreshToken')){
                return response()->json(['message' => 'Unauthenticated !!'],401);
            }
                auth('api')->setToken($request->cookie('refreshToken'))->authenticate();
            } catch (Exception){
                return response()->json(['message' => 'Unauthenticated !!'],401);
        }
        return $next($request);
    }
}

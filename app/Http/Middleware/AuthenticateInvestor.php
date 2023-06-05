<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateInvestor
{
    public function handle(Request $request, Closure $next)
    {
        if (auth('api_investors')->guest()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }
    
        return $next($request);
    }
}

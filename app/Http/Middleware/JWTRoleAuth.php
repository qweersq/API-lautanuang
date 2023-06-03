<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTRoleAuth extends BaseMiddleware{
    /** 
     * Handle an incoming request.
     * 
     * @param $request
     * @param Closure $next
     * @param null $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {
        try{
            // Resolve token role
            $token_role = $this->auth->parseToken()->getClaim('role');
        } catch (JWTException $e){
            /**
             * Token pairsing failed,indicating that there are no available tokens in the request.
             * In order to be used globally (request that do not require a token can also be passed), let the request continue here.
             * Because the responsibility of this middleware is only to verify the role in the token.
             */
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Judge the token role 
        if($token_role != $role) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return $next($request);
    }
}

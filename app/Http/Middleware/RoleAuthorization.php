<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            // Access token from the request        
            $token = JWTAuth::parseToken();
            // Try authenticating user       
            $user = $token->authenticate();
        } catch (TokenExpiredException $e) {
            // Thrown if token has expired        
            return $this->unauthorized('Token expirado');

        } catch (TokenInvalidException $e) {
            // Thrown if token invalid
            return $this->unauthorized('Token inválido');

        } catch (JWTException $e) {
            // Thrown if token was not found in the request.
            return $this->unauthorized('No se encontró el token');
        }
        
        // If user was authenticated successfully and user is in one of the acceptable roles, send to next request.
        if ($user && in_array($user->roles[0]->nombre, $roles)) {
            return $next($request);
        }
    
        return $this->unauthorized();
    }

    private function unauthorized($message = null) {
        return response()->json([
            'message' => $message ? $message : 'No autorizado'
        ], Response::HTTP_UNAUTHORIZED);
    }


}

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
            return $this->unauthorized('Token expirado');

        } catch (TokenInvalidException $e) {
            return $this->unauthorized('Token inválido');

        } catch (JWTException $e) {
            return $this->unauthorized('No se encontró el token');
        }
        
        if ($user && in_array($user->roles[0]->nombre, $roles)) {
            return $next($request);
        }
    
        return $this->unauthorized();
    }

    private function unauthorized($message = null) {
        return response()->json([
            'error'  => $message ? $message : 'No autorizado',
            'status' => 401
        ], Response::HTTP_UNAUTHORIZED);
    }


}

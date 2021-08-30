<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    // public function handle($request, Closure $next, $guard = null)
    // {
    //     if ($this->auth->guard($guard)->guest()) {
    //         return response()->json([
    //             'message' => 'No autorizado',
    //             'status'  => 401
    //         ], Response::HTTP_UNAUTHORIZED);
    //     }

    //     return $next($request);
    // }


    public function handle($request, Closure $next, $guard = null)
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
        if ($this->auth->guard($guard)->guest()) {
            return $this->unauthorized();
        }
    
        return $next($request);
    }

    private function unauthorized($message = null) {
        return response()->json([
            'message' => $message ? $message : 'No autorizado',
            'status'  => 401
        ], Response::HTTP_UNAUTHORIZED);
    }
}

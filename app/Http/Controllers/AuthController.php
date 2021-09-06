<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{   
    public function login(Request $request) {
        // Validar datos de entrada
        $this->validate($request, [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        // Validar si es un usuario desactivado
        if ( Auth::validate(array_merge($credentials, ['estado' => 0])) ) {
            return response()->json([
                'error'  => 'Usuario desactivado',
                'status' => 401 
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (! $token = Auth::attempt($credentials)) {
            return response()->json([
                'error'  => 'Datos incorrectos',
                'status' => 401 
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request) {
        // Auth::logout(); // Toma el token enviado en la cabecera
        Auth::logout($request->token);

        return response()->json([
            'message' => 'Cierre de sesión exitoso',
            'status'  => 200
        ], Response::HTTP_OK);
    }

    public function refresh(Request $request) {
        // $token = Auth::parseToken();
        // $refresh_token = Auth::refresh($token);

        $token = JWTAuth::parseToken($request->token);
        $refresh_token = JWTAuth::refresh($token);

        return $this->respondWithToken($refresh_token);
    }

    public function validateToken(Request $request) { 
        $token = JWTAuth::parseToken($request->token);
    }
}
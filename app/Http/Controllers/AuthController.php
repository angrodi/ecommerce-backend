<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{   
    public function login(Request $request) {
        // validate incoming request 
        $this->validate($request, [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Datos incorrectos',
                'status'  => 401 
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    public function logout() {
        Auth::logout();
        return response()->json([
            'message' => 'Cierre de sesión exitoso',
            'status'  => 200
        ], Response::HTTP_OK);
    }

    public function refresh() {
        $token = Auth::parseToken();
        $refresh_token = Auth::refresh($token);

        return $this->respondWithToken($refresh_token);
    }
}
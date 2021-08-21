<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            return response()->json(['message' => 'Datos incorrectos'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function registro(Request $request) {
        $usuario = new Usuario();
        $usuario->nombres   = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->dni       = $request->dni;
        $usuario->email     = $request->email;
        $usuario->password  = app('hash')->make($request->password);
        $usuario->telefono  = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->estado    = $request->estado;

        DB::beginTransaction();

        try {
            $usuario->save();
            $usuario->roles()->attach($request->rolId);
            DB::commit();

            return response()->json([
                'message' => 'Usuario creado exitosamente'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'Cierre de sesión exitoso'], 200);
    }

    public function refresh() {
        $token = Auth::parseToken();
        $refresh_token = Auth::refresh($token);

        return $this->respondWithToken($refresh_token);
    }
}
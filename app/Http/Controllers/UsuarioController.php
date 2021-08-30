<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function find(Request $request) { // para peticiones tipo: GET /api/usuarios?roles=admin;ventas;almacen
        $roles = $request->query('roles'); // obtiene: $roles = "admin;ventas;almacen" o null si no se manda "roles"

        if ($roles) {
            $arr_roles = explode(';', $roles);

            $usuarios = Usuario::with('roles')->whereHas('roles', function($q) use ($arr_roles) {
                $q->whereIn('nombre', $arr_roles);
            })->get();

        } else {
            $usuarios = Usuario::with('roles')->get();
        }

        return response()->json([
            'data'   => $usuarios,
            'status' => 200,
            'total'  => count($usuarios)
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        // $usuario = Usuario::findOrFail($id);
        $usuario = Usuario::with('roles')->findOrFail($id);

        return response()->json([
            'data'   => $usuario,
            'status' => 200
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {
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
                'message' => 'Usuario creado exitosamente',
                'status'  => 201
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, $id) {
        $usuario = Usuario::findOrFail($id);
        $usuario->nombres   = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->dni       = $request->dni;
        $usuario->email     = $request->email;
        $usuario->telefono  = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->estado    = $request->estado;

        if ($request->password) {
            $usuario->password = app('hash')->make($request->password);
        }

        DB::beginTransaction();

        try {
            $usuario->save();
            $usuario->roles()->sync($request->rolId);
            DB::commit();

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->delete()) {
            return response()->json([
                'message' => 'Usuario eliminado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }
}

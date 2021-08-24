<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function find() {
        $usuarios = Usuario::with('roles')->get();

        return response()->json([
            'data'  => $usuarios,
            'total' => count($usuarios)
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        // $usuario = Usuario::findOrFail($id);
        $usuario = Usuario::with('roles')->findOrFail($id);

        return response()->json([
            'data' => $usuario
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id) {
        $usuario = Usuario::findOrFail($id);
        $usuario->nombres   = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->dni       = $request->dni;
        $usuario->email     = $request->email;
        $usuario->password  = $request->password;
        $usuario->telefono  = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->estado    = $request->estado;

        DB::beginTransaction();

        try {
            $usuario->save();
            $usuario->roles()->sync($request->rolId);
            DB::commit();

            return response()->json([
                'message' => 'Usuario actualizado exitosamente'
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
                'message' => 'Usuario eliminado exitosamente'
            ], Response::HTTP_OK);
        }
    }
}

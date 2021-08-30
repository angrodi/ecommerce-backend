<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RolController extends Controller
{
    public function find() {
        $roles = Rol::all();

        return response()->json([
            'data'   => $roles,
            'status' => 200,
            'total'  => count($roles)
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        $rol = Rol::findOrFail($id);

        return response()->json([
            'data'   => $rol,
            'status' => 200
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {
        $rol = new Rol();
        $rol->nombre = $request->nombre;

        if ($rol->save()) {
            return response()->json([
                'message' => 'Rol creado exitosamente',
                'status'  => 201
            ], Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id) {
        $rol = Rol::findOrFail($id);
        $rol->nombre = $request->nombre;

        if ($rol->save()) {
            return response()->json([
                'message' => 'Rol actualizado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }

    public function delete($id) {
        $rol = Rol::findOrFail($id);

        if ($rol->delete()) {
            return response()->json([
                'message' => 'Rol eliminado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }
}

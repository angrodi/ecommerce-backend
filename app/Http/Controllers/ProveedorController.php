<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProveedorController extends Controller
{
    public function find() {
        $proveedores = Proveedor::all();

        return response()->json([
            'data'   => $proveedores,
            'total'  => count($proveedores),
            'status' => 200
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        $proveedor = Proveedor::findOrFail($id);

        return response()->json([
            'data'   => $proveedor,
            'status' => 200
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {
        $proveedor = new Proveedor();
        $proveedor->nombre    = $request->nombre;
        $proveedor->direccion = $request->direccion;
        $proveedor->telefono  = $request->telefono;
        $proveedor->ruc       = $request->ruc;

        if ($proveedor->save()) {
            return response()->json([
                'message' => 'Proveedor creado exitosamente',
                'status'  => 201
            ], Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id) {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->nombre    = $request->nombre;
        $proveedor->direccion = $request->direccion;
        $proveedor->telefono  = $request->telefono;
        $proveedor->ruc       = $request->ruc;

        if ($proveedor->save()) {
            return response()->json([
                'message' => 'Proveedor actualizado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }

    public function delete($id) {
        $proveedor = Proveedor::findOrFail($id);

        if ($proveedor->delete()) {
            return response()->json([
                'message' => 'Proveedor eliminado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClienteController extends Controller
{
    public function find(Request $request) { 
        $clientes = Cliente::all();

        return response()->json([
            'data'   => $clientes,
            'total'  => count($clientes),
            'status' => 200
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        $cliente = Cliente::findOrFail($id);

        return response()->json([
            'data'    => $cliente,
            'status'  => 200
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {
        $cliente = new Cliente();
        $cliente->nombres   = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->dni       = $request->dni;
        $cliente->telefono  = $request->telefono;
        $cliente->direccion = $request->direccion;

        if ($request->email) {
            $cliente->email = $request->email;
        }

        if ($cliente->save()) {
            return response()->json([
                'message' => 'Cliente creado exitosamente',
                'status'  => 201
            ], Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id) {
        $cliente = Cliente::findOrFail($id);
        $cliente->nombres   = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->dni       = $request->dni;
        $cliente->telefono  = $request->telefono;
        $cliente->direccion = $request->direccion;

        if ($request->email) {
            $cliente->email = $request->email;
        }

        if ($cliente->save()) {
            return response()->json([
                'message' => 'Cliente actualizado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }

    public function delete($id) {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->delete()) {
            return response()->json([
                'message' => 'Cliente eliminado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }
}

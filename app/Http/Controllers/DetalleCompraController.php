<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DetalleCompraController extends Controller
{
    public function find() {
        $detalles = DetalleCompra::all();

        return response()->json([
            'data'  => $detalles,
            'total' => count($detalles) 
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        $detalle = DetalleCompra::findOrFail($id);

        return response()->json([
            'data' => $detalle
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {
        $detalle = new DetalleCompra();
        $detalle->cantidad   = $request->cantidad;
        $detalle->precio     = $request->precio;
        $detalle->productoId = $request->productoId;
        $detalle->compraId   = $request->compraId;
        
        if ($detalle->save()) {
            return response()->json([
                'message' => 'Detalle de compra creada exitosamente'
            ], Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id) {
        $detalle = DetalleCompra::findOrFail($id);
        $detalle->cantidad   = $request->cantidad;
        $detalle->precio     = $request->precio;
        $detalle->productoId = $request->productoId;
        $detalle->compraId   = $request->compraId;

        if ($request->eliminado) {
            $detalle->eliminado = $request->eliminado;
        }

        if ($detalle->save()) {
            return response()->json([
                'message' => 'Detalle de compra actualizada exitosamente'
            ], Response::HTTP_OK);
        }
    }

    public function delete($id) {
        $detalle = DetalleCompra::findOrFail($id);

        if ($detalle->delete()) {
            return response()->json([
                'message' => 'Detalle de compra eliminada exitosamente'
            ], Response::HTTP_OK);
        }
    }
}

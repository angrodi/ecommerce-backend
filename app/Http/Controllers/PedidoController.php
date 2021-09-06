<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function find() {
        $pedidos = Pedido::all();

        return response()->json([
            'data'   => $pedidos,
            'status' => 200,
            'total'  => count($pedidos) 
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        // $pedido = Pedido::findOrFail($id);
        // $pedido = Pedido::with('detalles')->findOrFail($id);
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

        return response()->json([
            'data'   => $pedido,
            'status' => 200
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {

        DB::beginTransaction();

        try {
            $pedido = new Pedido();
            $pedido->direccion     = $request->direccion;
            $pedido->monto         = $request->monto;
            $pedido->metodoPago    = $request->metodoPago;
            $pedido->clienteId     = $request->clienteId;
            $pedido->estado        = $request->estado;

            if ($request->empleadoId) {
                $pedido->empleadoId = $request->empleadoId;
            }

            $pedido->save();

            $detalles = $request->detalles;
            
            // Guardar los detalles de la pedido
            foreach ($detalles as $detalle) {
                $detallePedido = new DetallePedido();
                $detallePedido->cantidad   = $detalle["cantidad"];
                $detallePedido->productoId = $detalle["productoId"];
                $detallePedido->pedidoId   = $pedido->id;

                $detallePedido->save();
                
                // Actualizar stock del producto
                $producto = Producto::findOrFail($detallePedido->productoId);
                $producto->stock = $producto->stock - $detallePedido->cantidad;
                
                $producto->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Pedido creado exitosamente',
                'status'  => 201
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, $id) {
        $pedido = Pedido::findOrFail($id);
        $pedido->direccion     = $request->direccion;
        $pedido->monto         = $request->monto;
        $pedido->metodoPago    = $request->metodoPago;
        $pedido->clienteId     = $request->clienteId;
        $pedido->estado        = $request->estado;

        if ($request->empleadoId) {
            $pedido->empleadoId = $request->empleadoId;
        }

        if ($pedido->save()) {
            return response()->json([
                'message' => 'Pedido actualizada exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }

    public function updatePatch(Request $request, $id) { // Metodo PATCH
        $pedido = Pedido::findOrFail($id);

        if ($request->estado) {
            $pedido->estado = $request->estado;
        }

        if ($pedido->save()) {
            return response()->json([
                'message' => 'Pedido actualizada exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }

    public function delete($id) {

        DB::beginTransaction();

        try {
            $pedido = Pedido::with('detalles')->findOrFail($id);

            $detalles = $pedido->detalles;

            foreach ($detalles as $detalle) {
                // Actualizar stock del producto
                $producto = Producto::findOrFail($detalle["productoId"]);
                $producto->stock = $producto->stock + $detalle["cantidad"];
                
                $producto->save();
            }

            $pedido->delete();

            DB::commit();

            return response()->json([
                'message' => 'Pedido eliminado exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

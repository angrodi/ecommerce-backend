<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function find() {
        $compras = Compra::all();

        return response()->json([
            'data'   => $compras,
            'status' => 200,
            'total'  => count($compras)
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        // $compra = Compra::findOrFail($id);
        $compra = Compra::with(['proveedor', 'detalles.producto'])->findOrFail($id);

        return response()->json([
            'data'   => $compra,
            'status' => 200
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {

        DB::beginTransaction();

        try {
            $compra = new Compra();
            $compra->monto       = $request->monto;
            $compra->fecha       = $request->fecha;
            $compra->proveedorId = $request->proveedorId;

            $compra->save();

            $detalles = $request->detalles;
            
            // Guardar los detalles de la compra
            foreach ($detalles as $detalle) {
                $detalleCompra = new DetalleCompra();
                $detalleCompra->cantidad   = $detalle["cantidad"];
                $detalleCompra->precio     = $detalle["precio"];
                $detalleCompra->productoId = $detalle["productoId"];
                $detalleCompra->compraId   = $compra->id;

                $detalleCompra->save();
                
                // Actualizar stock del producto
                $producto = Producto::findOrFail($detalleCompra->productoId);
                $producto->stock = $producto->stock + $detalleCompra->cantidad;
                
                $producto->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Compra creada exitosamente',
                'status'  => 201
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, $id) {
        $compra = Compra::findOrFail($id);
        $compra->fecha       = $request->fecha;
        $compra->proveedorId = $request->proveedorId;

        if ($compra->save()) {
            return response()->json([
                'message' => 'Compra actualizada exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }

    public function delete($id) {

        DB::beginTransaction();

        try {
            $compra = Compra::with('detalles')->findOrFail($id);

            $detalles = $compra->detalles;

            foreach ($detalles as $detalle) {
                // Actualizar stock del producto
                $producto = Producto::findOrFail($detalle["productoId"]);
                $producto->stock = $producto->stock - $detalle["cantidad"];
                
                $producto->save();
            }

            $compra->delete();

            DB::commit();

            return response()->json([
                'message' => 'Compra eliminada exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

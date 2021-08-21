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
            'data'  => $compras,
            'total' => count($compras) 
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        // $compra = Compra::findOrFail($id);
        $compra = Compra::with('detalles')->findOrFail($id);

        return response()->json([
            'data' => $compra
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
                'message' => 'Compra creada exitosamente'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, $id) {
        $compra = Compra::findOrFail($id);
        $compra->monto       = $request->monto;
        $compra->fecha       = $request->fecha;
        $compra->proveedorId = $request->proveedorId;

        if ($compra->save()) {
            return response()->json([
                'message' => 'Compra actualizada exitosamente'
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
                'message' => 'Compra eliminada exitosamente'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

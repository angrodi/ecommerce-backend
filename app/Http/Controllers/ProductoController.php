<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    
    public function find() {
        $productos = Producto::all();
        // $productos = Producto::with('categoria')->get();

        return response()->json([
            'data'  => $productos,
            'total' => count($productos) 
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        $producto = Producto::findOrFail($id);

        return response()->json([
            'data' => $producto
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {
        $producto = new Producto();
        $producto->nombre       = $request->nombre;
        $producto->descripcion  = $request->descripcion;
        $producto->precio       = $request->precio;
        $producto->stock        = $request->stock;
        $producto->categoriaId  = $request->categoriaId;
        
        // Subir imagen a AWS S3
        try {
            $path = $request->file('imagen')->store('images', 's3');

            /** @var \Illuminate\Filesystem\FilesystemManager $disk */
            $disk = Storage::disk('s3');
            $url = $disk->url($path);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $producto->imagen = $url;

        // Crear el producto
        if ($producto->save()) {
            return response()->json([
                'message' => 'Producto creado exitosamente'
            ], Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id) {
        $producto = Producto::findOrFail($id);
        $producto->nombre       = $request->nombre;
        $producto->descripcion  = $request->descripcion;
        $producto->imagen       = $request->imagen;
        $producto->precio       = $request->precio;
        $producto->stock        = $request->stock;
        $producto->estado       = $request->estado;
        $producto->categoriaId  = $request->categoriaId;

        if ($request->eliminado) {
            $producto->eliminado = $request->eliminado;
        }

        if ($producto->save()) {
            return response()->json([
                'message' => 'Producto actualizado exitosamente'
            ], Response::HTTP_OK);
        }
    }

    public function delete($id) {
        $producto = Producto::findOrFail($id);

        if ($producto->delete()) {
            return response()->json([
                'message' => 'Producto eliminado exitosamente'
            ], Response::HTTP_OK);
        }
    }
}

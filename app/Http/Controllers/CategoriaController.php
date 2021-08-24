<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoriaController extends Controller
{   
    // public function __construct() {
    //     $this->middleware('auth');
    // }

    public function find() {
        $categorias = Categoria::all();

        return response()->json([
            'data'   => $categorias,
            'total'  => count($categorias),
            'status' => 200
        ], Response::HTTP_OK);
    }

    public function findById($id) {
        $categoria = Categoria::findOrFail($id);

        return response()->json([
            'data'    => $categoria,
            'status'  => 200
        ], Response::HTTP_OK);
    }

    public function create(Request $request) {
        $categoria = new Categoria();
        $categoria->nombre      = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->estado      = $request->estado;

        if ($categoria->save()) {
            return response()->json([
                'message' => 'Categoría creada exitosamente',
                'status'  => 201
            ], Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id) {
        $categoria = Categoria::findOrFail($id);
        $categoria->nombre      = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->estado      = $request->estado;

        if ($categoria->save()) {
            return response()->json([
                'message' => 'Categoría actualizada exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }

    public function delete($id) {
        $categoria = Categoria::findOrFail($id);

        if ($categoria->delete()) {
            return response()->json([
                'message' => 'Categoría eliminada exitosamente',
                'status'  => 200
            ], Response::HTTP_OK);
        }
    }
}

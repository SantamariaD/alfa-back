<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function consultarCategorias()
    {
        $productos = Categoria::all();
        return response()->json(Respuestas::respuesta200('Categorías encontradas.', $productos));
    }

    public function crearCategoria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoria' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $categoria = Categoria::create($request->all());

        return response()->json(Respuestas::respuesta200('Categoria creado.', $categoria), 201);
    }

    public function eliminarCategoria($id)
    {
        $categoria = Categoria::find($id);

        Producto::where('categoria', $id)->update(['categoria' => 1]);

        if (!$categoria) {
            return response()->json(Respuestas::respuesta404('Categoria no encontrado'));
        }

        $categoria->delete();

        return response()->json(Respuestas::respuesta200NoResultados('Categoria eliminado'));
    }
}

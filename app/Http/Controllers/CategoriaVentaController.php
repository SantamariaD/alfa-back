<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Respuestas\Respuestas;
use App\Models\CategoriaVenta;
use App\Models\StockVenta;
use Illuminate\Support\Facades\Validator;

class CategoriaVentaController extends Controller
{
    public function consultarCategoriasVentas()
    {
        $categorias = CategoriaVenta::all();
        return response()->json(Respuestas::respuesta200('Categorías encontradas.', $categorias));
    }

    public function crearCategoriaVentas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoria' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        $categoria = CategoriaVenta::create($request->all());

        return response()->json(Respuestas::respuesta200('Categoria creado.', $categoria), 201);
    }

    public function eliminarCategoriaVentas($id)
    {
        $categoria = CategoriaVenta::find($id);

        StockVenta::where('idCategoria', $id)->update(['categoria' => 1]);

        if (!$categoria) {
            return response()->json(Respuestas::respuesta404('Categoria no encontrado'), 404);
        }

        $categoria->delete();

        return response()->json(Respuestas::respuesta200NoResultados('Categoria eliminado'));
    }
}
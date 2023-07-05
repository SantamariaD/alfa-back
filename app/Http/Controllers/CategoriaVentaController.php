<?php

namespace App\Http\Controllers;

use App\Models\CategoriaVenta;
use App\Models\StockVenta;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
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

        CategoriaVenta::create($request->all());

        $categorias = CategoriaVenta::all();

        return response()->json(Respuestas::respuesta200('Categoria creado.', $categorias), 201);
    }

    public function eliminarCategoriaVentas($id)
    {
        $categoria = CategoriaVenta::find($id);

        StockVenta::where('idCategoria', $id)->update(['idCategoria' => 1]);

        if (!$categoria) {
            return response()->json(Respuestas::respuesta404('Producto no encontrado'), 404);
        }

        $categoria->delete();
        $categorias = CategoriaVenta::all();
        $productos = StockVenta::where('eliminado', false)
            ->join(
                'categorias_stock_ventas',
                'stock_ventas.idCategoria',
                '=',
                'categorias_stock_ventas.id'
            )
            ->select('stock_ventas.*', 'categorias_stock_ventas.categoria')
            ->orderBy('stock_ventas.nombreProducto', 'asc')
            ->get();

        $respuesta = [
            'categorias' => $categorias,
            'productos' => $productos,
        ];

        return response()->json(Respuestas::respuesta200('Categoria eliminado', $respuesta));
    }
}

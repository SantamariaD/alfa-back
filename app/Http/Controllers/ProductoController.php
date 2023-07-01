<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function consultarProductos()
    {
        $productos = DB::table('stock_compras')
            ->join('categorias_stock_compras', 'stock_compras.categoria', '=', 'categorias_stock_compras.id')
            ->select('stock_compras.*', 'categorias_stock_compras.categoria', 'categorias_stock_compras.id AS idCategoria')
            ->orderBy('stock_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Productos encontrados.', $productos));
    }

    public function crearProducto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'codigoBarras' => 'required',
            'categoria' => 'required',
            'cantidadStock' => 'required',
            'ventas' => 'required',
            'sku' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        Producto::create($request->all());
        $producto = DB::table('stock_compras')
            ->join('categorias_stock_compras', 'stock_compras.categoria', '=', 'categorias_stock_compras.id')
            ->select('stock_compras.*', 'categorias_stock_compras.categoria', 'categorias_stock_compras.id AS idCategoria')
            ->orderBy('stock_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto creado.', $producto), 201);
    }

    public function consultarProducto($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(Respuestas::respuesta404('Producto no encontrado'));
        }

        return response()->json(Respuestas::respuesta200('Producto encontrado.', $producto));
    }

    public function actualizarProducto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nombre' => 'nullable',
            'descripcion' => 'nullable',
            'codigoBarras' => 'nullable',
            'categoria' => 'nullable',
            'cantidadStock' => 'nullable',
            'ventas' => 'nullable',
            'agotado' => 'boolean|nullable',
            'sku' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id' => $request->id,
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'descripcion' => $request->descripcion,
            'codigoBarras' => $request->codigoBarras,
            'cantidadStock' => $request->cantidadStock,
            'ventas' => $request->ventas,
            'sku' => $request->sku,
            'agotado' => $request->agotado,
        ];

        $datosActualizado = array_filter($datosActualizado);

        Producto::where('id', $request->input('id'))
            ->update($datosActualizado);

        $productos = DB::table('stock_compras')
            ->join('categorias_stock_compras', 'stock_compras.categoria', '=', 'categorias_stock_compras.id')
            ->select('stock_compras.*', 'categorias_stock_compras.categoria', 'categorias_stock_compras.id AS idCategoria')
            ->orderBy('stock_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto actualizado.', $productos));
    }

    public function eliminarProducto($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(Respuestas::respuesta404('Producto no encontrado'));
        }

        $producto->delete();

        $productos = DB::table('stock_compras')
            ->join('categorias_stock_compras', 'stock_compras.categoria', '=', 'categorias_stock_compras.id')
            ->select('stock_compras.*', 'categorias_stock_compras.categoria', 'categorias_stock_compras.id AS idCategoria')
            ->orderBy('stock_compras.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto eliminado', $productos));
    }
}

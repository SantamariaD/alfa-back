<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockVenta;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class StockVentaController extends Controller
{
    public function consultarProductosVentas()
    {
        $productos = StockVenta::join(
            'categorias_stock_ventas',
            'stock_ventas.idCategoria',
            '=',
            'categorias_stock_ventas.id'
        )
            ->select('stock_ventas.*', 'categorias_stock_ventas.categoria')
            ->orderBy('stock_ventas.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Productos encontrados.', $productos));
    }

    public function crearProductoVentas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'codigoBarras' => 'required',
            'precioVenta' => 'required',
            'categoria' => 'required',
            'sku' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        StockVenta::create($request->all());
        $producto = StockVenta::join(
            'categorias_stock_ventas',
            'stock_ventas.idCategoria',
            '=',
            'categorias_stock_ventas.id'
        )
            ->select('stock_ventas.*', 'categorias_stock_ventas.categoria')
            ->orderBy('stock_ventas.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto creado.', $producto), 201);
    }

    public function actualizarProductoVentas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nombre' => 'nullable',
            'descripcion' => 'nullable',
            'codigoBarras' => 'nullable',
            'idCategoria' => 'nullable',
            'precioVenta' => 'nullable',
            'cantidadStock' => 'nullable',
            'ventas' => 'nullable',
            'agotado' => 'nullable',
            'sku' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'id' => $request->id,
            'nombreProducto' => $request->nombreProducto,
            'idCategoria' => $request->idCategoria,
            'descripcion' => $request->descripcion,
            'codigoBarras' => $request->codigoBarras,
            'cantidadStock' => $request->cantidadStock,
            'precioVenta' => $request->precioVenta,
            'ventasTotales' => $request->ventas,
            'sku' => $request->sku,
            'agotado' => $request->agotado,
        ];

        $datosActualizado = array_filter($datosActualizado);

        if ($request->agotado != 1) {
            $datosActualizado['agotado'] = 0;
        }

        StockVenta::where('id', $request->input('id'))
            ->update($datosActualizado);

        $productos = StockVenta::join(
            'categorias_stock_ventas',
            'stock_ventas.idCategoria',
            '=',
            'categorias_stock_ventas.id'
        )
            ->select('stock_ventas.*', 'categorias_stock_ventas.categoria')
            ->orderBy('stock_ventas.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto actualizado.', $productos));
    }

    public function eliminarProductoVentas($id)
    {
        $producto = StockVenta::find($id);

        if (!$producto) {
            return response()->json(Respuestas::respuesta404('Producto no encontrado'));
        }

        $producto->delete();

        $productos = StockVenta::join(
            'categorias_stock_ventas',
            'stock_ventas.idCategoria',
            '=',
            'categorias_stock_ventas.id'
        )
            ->select('stock_ventas.*', 'categorias_stock_ventas.categoria')
            ->orderBy('stock_ventas.nombreProducto', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto eliminado', $productos));
    }
}
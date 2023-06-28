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
        $productos = DB::table('productos')
            ->join('categorias_productos', 'productos.categoria', '=', 'categorias_productos.id')
            ->select('productos.*', 'categorias_productos.categoria', 'categorias_productos.id AS idCategoria')
            ->orderBy('productos.nombre', 'asc')
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
            'precioVenta' => 'required',
            'cantidadStock' => 'required',
            'fechaCompra' => 'required',
            'sku' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        Producto::create($request->all());
        $producto = DB::table('productos')
            ->join('categorias_productos', 'productos.categoria', '=', 'categorias_productos.id')
            ->select('productos.*', 'categorias_productos.categoria', 'categorias_productos.id AS idCategoria')
            ->orderBy('productos.nombre', 'asc')
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
            'precioVenta' => 'nullable',
            'cantidadStock' => 'nullable',
            'fechaCompra' => 'nullable',
            'imagen' => 'nullable',
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
            'precioVenta' => $request->precioVenta,
            'cantidadStock' => $request->cantidadStock,
            'fechaCompra' => $request->fechaCompra,
            'imagen' => $request->imagen,
            'sku' => $request->sku,
            'ventas' => $request->ventas,
            'agotado' => $request->agotado,
        ];

        $datosActualizado = array_filter($datosActualizado);

        Producto::where('id', $request->input('id'))
            ->update($datosActualizado);

        $productos = DB::table('productos')
            ->join('categorias_productos', 'productos.categoria', '=', 'categorias_productos.id')
            ->select('productos.*', 'categorias_productos.categoria', 'categorias_productos.id AS idCategoria')
            ->orderBy('productos.nombre', 'asc')
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

        $productos = DB::table('productos')
            ->join('categorias_productos', 'productos.categoria', '=', 'categorias_productos.id')
            ->select('productos.*', 'categorias_productos.categoria', 'categorias_productos.id AS idCategoria')
            ->orderBy('productos.nombre', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Producto eliminado', $productos));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Respuestas\Respuestas;

class ProductoController extends Controller
{
    public function consultarProductos()
    {
        $productos = Producto::all();
        return response()->json(Respuestas::respuesta200('Productos encontrados.', $productos));
    }

    public function crearProducto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'codigo' => 'required',
            'categoria' => 'required',
            'proveedor' => 'required',
            'precioCompra' => 'required',
            'precioVenta' => 'required',
            'cantidadStock' => 'required',
            'fechaCompra' => 'required',
            'imagen' => 'required',
            'agotado' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $producto = Producto::create($request->all());

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
            'nombre' => 'required',
            'descripcion' => 'required',
            'codigo' => 'required',
            'categoria' => 'required',
            'proveedor' => 'required',
            'precioCompra' => 'required',
            'precioVenta' => 'required',
            'cantidadStock' => 'required',
            'fechaCompra' => 'required',
            'imagen' => 'required',
            'agotado' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $producto = Producto::find($request->id);

        if (!$producto) {
            return response()->json(Respuestas::respuesta404('Producto no encontrado'));
        }

        $producto->update($request->all());

        return response()->json(Respuestas::respuesta200('Producto actualizado.', $producto));
    }

    public function eliminarProducto($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(Respuestas::respuesta404('Producto no encontrado'));
        }

        $producto->delete();

        return response()->json(Respuestas::respuesta200NoResultados('Producto eliminado'));
    }
}

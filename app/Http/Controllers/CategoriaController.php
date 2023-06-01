<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Respuestas\Respuestas;

class CategoriaController extends Controller
{
    public function consultarCategorias()
    {
        $productos = Categoria::all();
        return response()->json(Respuestas::respuesta200('Categorías encontradas.', $productos));
    }
}

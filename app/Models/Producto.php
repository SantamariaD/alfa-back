<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigoBarras',
        'sku',
        'categoria',
        'proveedor',
        'precioCompra',
        'precioVenta',
        'cantidadStock',
        'ventas'.
        'fechaCompra',
        'imagen',
        'agotado',
    ];
}

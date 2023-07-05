<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockVenta extends Model
{
    use HasFactory;
    protected $table = 'stock_ventas';

    protected $fillable = [
        'nombreProducto',
        'descripcion',
        'codigoBarras',
        'sku',
        'idCategoria',
        'ventasTotales',
        'cantidadStock',
        'agotado',
        'precioVenta',
    ];
}

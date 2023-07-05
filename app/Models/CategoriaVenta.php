<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaVenta extends Model
{
    use HasFactory;
    protected $table = 'categorias_stock_ventas';
    protected $fillable = [
        'id', 
        'categoria',
    ];
}

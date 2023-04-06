<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $table = 'documentos';
    protected $fillable = [
        'id', 
        'id_user',
        'nombre_archivo',
        'uuid',
        'area',
        'extension',
        'activo'
    ];


    public function getOrdenadosPorArea()
    {
        return $this->orderBy('area')->get();
    }
}

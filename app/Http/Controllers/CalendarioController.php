<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendario;
use Carbon\Carbon;
use App\Respuestas\Respuestas;

class CalendarioController extends Controller
{
    public function consultarCalendarioUsuario()
    {
        $year = Carbon::now()->year;
        $userId = 1;

        $registros = Calendario::where('idUsuario', $userId)
            ->whereYear('fecha', $year)
            ->get();

        $listDataMap = [];

        foreach ($registros as $registro) {
            $dia = Carbon::parse($registro->fecha)->day;
            $mes = Carbon::parse($registro->fecha)->month;
            $ano = Carbon::parse($registro->fecha)->year;

            if (!isset($listDataMap[$dia])) {
                $listDataMap[$dia] = [];
            }

            $listDataMap[$dia][] = [
                'idUsuario' => $registro->idUsuario,
                'tipo' => $registro->tipo,
                'contenido' => $registro->contenido,
                'dia' => $dia,
                'mes' => $mes,
                'ano' => $ano,
                'resuelto' => $registro->resuelto,
            ];
        }

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $listDataMap
            )
        );
    }
}

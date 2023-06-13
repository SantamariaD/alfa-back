<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Respuestas\Respuestas;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function consultarTodosEmpleados()
    {
        $empleados = Empleado::all();
        return response()->json(Respuestas::respuesta200('Empleados encontrados.', $empleados));
    }

    public function crearEmpleado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'nullable',
            'apellido_paterno' => 'nullable',
            'apellido_materno' => 'nullable',
            'fecha_nacimiento' => 'nullable',
            'genero' => 'nullable',
            'estado_civil' => 'nullable',
            'curp' => 'nullable',
            'rfc' => 'nullable',
            'nss' => 'nullable',
            'direccion' => 'nullable',
            'telefono' => 'nullable',
            'correo_electronico' => 'nullable',
            'puesto' => 'nullable',
            'departamento' => 'nullable',
            'fecha_inicio' => 'nullable',
            'salario' => 'nullable',
            'horas_laborales' => 'nullable',
            'tipo_contrato' => 'nullable',
            'fecha_alta' => 'nullable',
            'fecha_baja' => 'nullable',
           
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $empleado = Empleado::create($request->all());

        return response()->json(Respuestas::respuesta200('Empleado creado.', $empleado), 201);
    }

    public function consultarEmpleado($id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(Respuestas::respuesta404('Empleado no encontrado'));
        }

        return response()->json(Respuestas::respuesta200('Empleado encontrado.', $empleado));
    }

    public function actualizarEmpleado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nombres' => 'nullable',
            'apellido_paterno' => 'nullable',
            'apellido_materno' => 'nullable',
            'fecha_nacimiento' => 'nullable',
            'genero' => 'nullable',
            'estado_civil' => 'nullable',
            'curp' => 'nullable',
            'rfc' => 'nullable',
            'nss' => 'nullable',
            'direccion' => 'nullable',
            'telefono' => 'nullable',
            'correo_electronico' => 'nullable',
            'puesto' => 'nullable',
            'departamento' => 'nullable',
            'fecha_inicio' => 'nullable',
            'salario' => 'nullable',
            'horas_laborales' => 'nullable',
            'tipo_contrato' => 'nullable',
            'fecha_alta' => 'nullable',
            'fecha_baja' => 'nullable',
            'baja' => 'nullable',
            'fecha_reingreso' => 'nulleable'
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizados = [
            'id' => $request->id,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'estado_civil' => $request->estado_civil,
            'curp' => $request->curp,
            'rfc' => $request->rfc,
            'nss' => $request->nss,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo_electronico' => $request->correo_electronico,
            'puesto' => $request->puesto,
            'departamento' => $request->departamento,
            'fecha_inicio' => $request->fecha_inicio,
            'salario' => $request->salario,
            'horas_laborales' => $request->horas_laborales,
            'tipo_contrato' => $request->tipo_contrato,
            'fecha_alta' => $request->fecha_alta,
            'fecha_baja' => $request->fecha_baja,
            'baja' => $request->baja,
            'fecha_reingreso' => $request->fecha_reingreso,
        ];

        $datosActualizados = array_filter($datosActualizados);

        Empleado::where('id', $request->input('id'))
            ->update($datosActualizados);

        return response()->json(Respuestas::respuesta200NoResultados('Empleado actualizado.'));
    }


    public function eliminarEmpleado($id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(Respuestas::respuesta404('Empleado no encontrado'));
        }

        $empleado->delete();

        return response()->json(Respuestas::respuesta200NoResultados('Empleado eliminado'));
    }
}

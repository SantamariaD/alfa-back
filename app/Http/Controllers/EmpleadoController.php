<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Respuestas\Respuestas;
use App\Models\Empleado;
use Illuminate\Support\Facades\Storage;
use App\Models\DocEmpleado;
use Illuminate\Support\Str;

class EmpleadoController extends Controller
{
    private $UUID;

    public function consultarTodosEmpleados()
    {
        $empleados = Empleado::orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->get();
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
            'fecha_reingreso' => 'nullable'
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
        if ($request->baja == 0) {
            $datosActualizados['baja'] = 0;
        }

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

    public function guardarArchivo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_emp' => 'int|required',
            'file0' => 'required',
            'area' => 'required',
            'nombre_archivo' => 'required',
            'estatus' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }


        $datos_request = array_map('trim', $request->all());

        $archivo = $request->file('file0');
        $area = $request->input('area');
        $UUID = Str::orderedUuid();
        $extension = $archivo->getClientOriginalExtension();

        $documento = new DocEmpleado();
        $documento->id_emp = $datos_request['id_emp'];
        $documento->nombre_archivo = $datos_request['nombre_archivo'];
        $documento->area = $datos_request['area'];
        $documento->uuid = $UUID;
        $documento->extension = $extension;

        $documento->save();

        $archivo->storeAs(
            "/" . $area,
            $UUID . '.' . $extension,
            'empleados'
        );

        $documentoRespuesta = DocEmpleado::where('uuid', $UUID)->get();

        return response()->json(Respuestas::respuesta200('Archivo guardado.', $documentoRespuesta[0]));
    }

    public function traerArchivo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uuid' => 'string|required',
            'extension' => 'string|required',
            'area' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $UUID = $request->input('uuid');
        $extension = $request->input('extension');
        $area = $request->input('area');

        return Storage::disk('empleados')->get($area . '/' . $UUID . "." . $extension);
    }

    public function descargarArchivo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'string|required',
            'extension' => 'string|required',
            'area' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $UUID = $request->input('uuid');
        $extension = $request->input('extension');
        $area = $request->input('area');

        return Storage::disk('empleados')->download($area . '/' . $UUID . "." . $extension);
    }

    public function traerTodosDocumentos()
    {
        /**
         *  Método para consultaer todos los documentos ordenados alfabeticamente por área
         */
        $documentos = new DocEmpleado;
        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $documentos->getOrdenadosPorArea()
            )
        );
    }

    public function traerDocumentosArea($area)
    {
        /**
         *  Método para consultaer todos los documentos de una área
         */

        if (!$area) {
            return response()->json(Respuestas::respuesta400('No se tiene el área a buscar.'));
        }

        $documentos = DocEmpleado::where('area', $area)->where('activo', true)->get();

        if (count($documentos) < 1) {
            return response()->json(Respuestas::respuesta400('El área no se encontro.'));
        }

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $documentos
            )
        );
    }

    public function actualizarDocumento(Request $request)
    {
        /**
         *  Método para actualizar un documento
         */

        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
            'id_emp' => 'int|nullable',
            'nombre_archivo' => 'string|nullable',
            'uuid' => 'string|nullable',
            'file0' => 'nullable',
            'extension' => 'string|nullable',
            'area' => 'string|nullable',
            'areaNueva' => 'string|nullable',
            'activo' => 'boolean|nullable',
            'estatus' => 'string|nullable'
        ]);

        $extensionNueva = '';

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        if (
            $request->has('file0') &&
            $request->has('extension') &&
            $request->has('area') &&
            $request->has('uuid')
        ) {
            // CASO1: Se actualiza el documento y el archivo
            Storage::delete('empleados/' . $request->area . '/' . $request->uuid . '.' . $request->extension);

            $archivo = $request->file('file0');
            $area = $request->input('area');
            $extensionNueva = $archivo->getClientOriginalExtension();
            $this->UUID = Str::orderedUuid();

            $archivo->storeAs(
                "/" . $area,
                $this->UUID . '.' . $extensionNueva,
                'empleados'
            );
        } elseif ($request->has('areaNueva')) {
            // CASO 2: Se actualiza el area
            Storage::move(
                'empleados/' . $request->area . '/' . $request->uuid . '.' .
                    $request->extension,
                'empleados/' . $request->areaNueva . '/' .
                    $request->uuid . '.' . $request->extension
            );
        } else {
            // CASO 3: Se actualiza lo demás
            $this->UUID = $request->uuid;
        }

        $datosActualizado = [
            'id_emp' => $request->id_emp,
            'nombre_archivo' => $request->nombre_archivo,
            'uuid' => $this->UUID,
            'extension' => $extensionNueva,
            'area' => $request->areaNueva,
            'activo' => $request->activo,
            'estatus' => $request->estatus,
        ];

        $datosActualizado = array_filter($datosActualizado);


        if ($request->has('activo')) {
            $datosActualizado = [
                'activo' => false,
            ];
        }

        DocEmpleado::where('id', $request->input('id'))
            ->update($datosActualizado);

        $documentoRespuesta = DocEmpleado::where('id', $request->input('id'))->get();

        return response()->json(Respuestas::respuesta200('Se actualizó el documento.', $documentoRespuesta[0]));
    }

    public function borrarDocumento(Request $request)
    {
        /**
         *  Método para borrar un documento
         */

        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $datosActualizado = [
            'activo' => false,
        ];
        DocEmpleado::where('id', $request->input('id'))
            ->update($datosActualizado);

        return response()->json(Respuestas::respuesta200NoResultados('Se borro correctamente el documento.'));
    }

    public function descargarDocumento($uuid, $extension, $area, $nombre_archivo)
    {
        /**
         *  Método para borrar un documento
         */

        if (!$uuid) {
            return response()->json(Respuestas::respuesta400('No se tiene uuid'));
        }

        $ruta = '/empleados/' . $area . '/' . $uuid . '.' . $extension;
        return Storage::download(
            $ruta,
            $nombre_archivo .
                '.' .
                $extension
        );
    }
}

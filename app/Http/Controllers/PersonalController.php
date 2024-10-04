<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Personal;
use Inertia\Inertia;

class PersonalController extends Controller
{
    public function edit(Request $request)
    {
        $idEmpleado = $request->query('idEmpleado');
        
        $personal = Personal::find($idEmpleado);

        if (!$personal) {
            return redirect()->route('personal.index');
        }

        return Inertia::render('Personal/edit', [
            'personal' => $personal
        ]);
    }

    public function update(Request $request, $idEmpleado)
    {
        
        // Validar los datos
        $validatedData = $request->validate([
            'nombresEmpleado' => 'required',
            'apellidoPaternoEmple' => 'nullable',
            'apellidoMaternoEmple' => 'nullable',
            'tipoDocumento' => 'required',
            'numDocEmple' => 'nullable',
            'celularEmple' => 'nullable',
            'direccionEmple' => 'nullable',
            'estado' => 'required',
            'grupo' => 'required',
            'codigo' => 'required',
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'max' => 'El campo :attribute no puede tener más de :max caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'numeric' => 'El campo :attribute debe ser numérico.',
        ], [
            'nombresEmpleado' => 'nombre',
            'apellidoPaternoEmple' => 'apellido paterno',
            'apellidoMaternoEmple' => 'apellido materno',
            'tipoDocumento' => 'tipo de documento',
            'numDocEmple' => 'número de documento',
            'celularEmple' => 'celular',
            'direccionEmple' => 'dirección',
            'estado' => 'estado',
            'codigo' => 'codigo',
        ]);

        // Buscar el empleado por ID
        $personal = Personal::find($idEmpleado);

        if (!$personal) {
            return redirect()->route('personal.index');
        }

        // Actualizar los datos del empleado
        $personal->update($validatedData);

        // Redirigir a una ruta de éxito con un mensaje
        return redirect()->route('personal.index')->with('success', 'Datos del empleado actualizados correctamente');
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'nombresEmpleado' => 'required',
            'apellidoPaternoEmple' => 'nullable',
            'apellidoMaternoEmple' => 'nullable',
            'tipoDocumento' => 'required',
            'numDocEmple' => 'nullable',
            'celularEmple' => 'nullable',
            'direccionEmple' => 'nullable',
            'estado' => 'required',
            'grupo' => 'required',
            'codigo' => 'required',
            'fecha_registro' => 'nullable',
            'hora_registro' => 'nullable',
            'user_registro' => 'nullable',
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'max' => 'El campo :attribute no puede tener más de :max caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'numeric' => 'El campo :attribute debe ser numérico.',
        ], [
            'nombresEmpleado' => 'nombre',
            'apellidoPaternoEmple' => 'apellido paterno',
            'apellidoMaternoEmple' => 'apellido materno',
            'tipoDocumento' => 'tipo de documento',
            'numDocEmple' => 'número de documento',
            'celularEmple' => 'celular',
            'direccionEmple' => 'dirección',
            'estado' => 'estado',
            'codigo' => 'codigo',
        ]);

        // Crear un nuevo registro en la base de datos
        Personal::create($validatedData);

        // Redirigir a una ruta de éxito con un mensaje
        return redirect()->route('personal.index')->with('success', 'Registro creado exitosamente');
    }

    public function traerDatosTrabajadores(){
        $datos = DB::select('
            SELECT
            IFNULL(CONCAT_WS(" ", nombresEmpleado, apellidoPaternoEmple, apellidoMaternoEmple), "") AS nombreCompleto,
            idEmpleado,
            direccionEmple,
            tipoDocumento,
            numDocEmple,
            celularEmple,
            estado,
            codigo,
            grupo
            FROM tb_empleados
        ');
        return response()->json($datos);
    }

    public function traerDatosServis(){
        $datos = DB::select('
            SELECT
            nombreGrupo,
            idGrupo
            FROM tb_grupos
            ORDER BY nombreGrupo ASC
        ');
        return response()->json($datos);
    }
    
    public function traerCodigoPersonal(Request $request){
        $grupo = $request->input('grupo');

        $rango = DB::select('
            SELECT rango 
            FROM tb_grupos 
            WHERE idGrupo = ?
        ', [$grupo]);
    
        if (empty($rango)) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        $rangoValores = explode('-', $rango[0]->rango);
        $rangoMin = (int)$rangoValores[0] + 1;
    
        $datos = DB::select('
            SELECT IFNULL(MAX(CONVERT(codigo, SIGNED))+1, ?) AS cant_cod 
            FROM tb_empleados 
            WHERE grupo = ?
        ', [$rangoMin, $grupo]);
    
        return response()->json($datos);
    }
    
    public function crearNuevoServis(Request $request) {
        $nombreServis = $request->input('nombreServis');
    
        $rango = DB::select('
            SELECT rango 
            FROM tb_grupos 
            ORDER BY idGrupo DESC 
            LIMIT 1
        ');
    
        if (empty($rango)) {
            $rangoMin = 0;
        } else {
            $rangoValores = explode('-', $rango[0]->rango);
            $rangoMin = (int)$rangoValores[1];
        }
    
        $rangoMax = $rangoMin + 1000;
        $rangoNuevo = $rangoMin . '-' . $rangoMax;
    
        DB::insert('
            INSERT INTO tb_grupos (nombreGrupo, rango) 
            VALUES (?, ?)
        ', [$nombreServis, $rangoNuevo]);
    
        $idDelGrupo = DB::getPdo()->lastInsertId();

        DB::insert('
            INSERT INTO tb_precio_x_presentacion 
            (idGrupo, preTalloSolo, preTalloCoral, preMediaValvaTS, preMediaValvaTC, preOtros) 
            VALUES (?, 0, 0, 0, 0, 0)
        ', [$idDelGrupo]);
    
        return response()->json(['success' => true]);
    }  

}

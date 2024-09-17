<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
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
            ORDER BY nombreCompleto ASC
        ');
        return response()->json($datos);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReporteController extends Controller
{    
    public function traerDatosReporteDiario(Request $request){
        $fecha = $request->input('fecha');
        $grupo = $request->input('grupo');

        $datos = DB::select('
            SELECT 
                tb_pesadas.codigoUsuario,
                IFNULL(CONCAT_WS(" ", nombresEmpleado, apellidoPaternoEmple, apellidoMaternoEmple), "") AS nombreCompleto,
                tb_pesadas.especie,
                SUM(tb_pesadas.pesoNeto) AS totalPesoNeto,
                SUM(tb_pesadas.tara) AS totalTara,
                IFNULL(SUM(tb_descuentos.pesoDescuento), 0) AS pesoDescuento
            FROM tb_pesadas
            INNER JOIN tb_empleados ON tb_empleados.codigo = tb_pesadas.codigoUsuario
            LEFT JOIN tb_descuentos ON tb_descuentos.codigoUsuario = tb_pesadas.codigoUsuario
                AND tb_descuentos.especie = tb_pesadas.especie
                AND tb_descuentos.fecha = tb_pesadas.fech_InicioProc
            WHERE fech_InicioProc = ? 
                AND tb_empleados.grupo = ?
            GROUP BY tb_pesadas.especie, tb_pesadas.codigoUsuario, nombreCompleto
            ORDER BY tb_pesadas.codigoUsuario ASC;
            ',[$fecha, $grupo]);

        return response()->json($datos);
    }

    public function traerPreciosServis(Request $request){
        $grupo = $request->input('grupo');

        $datos = DB::select('
            SELECT 
            preTalloSolo,
            preTalloCoral,
            preMediaValvaTS,
            preMediaValvaTC,
            preOtros
            FROM tb_precio_x_presentacion
            WHERE idGrupo = ?
        ',[$grupo]);

        return response()->json($datos);
    }
}

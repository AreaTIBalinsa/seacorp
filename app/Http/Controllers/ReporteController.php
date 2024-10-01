<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReporteController extends Controller
{    

    public function traerPreciosServisGrupo(Request $request){
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
                (
                    SELECT IFNULL(SUM(tb_descuentos.pesoDescuento), 0)
                    FROM tb_descuentos 
                    WHERE tb_descuentos.codigoUsuario = tb_pesadas.codigoUsuario
                    AND tb_descuentos.especie = tb_pesadas.especie
                    AND tb_descuentos.fecha = tb_pesadas.fech_InicioProc
                ) AS pesoDescuento
            FROM tb_pesadas
            INNER JOIN tb_empleados ON tb_empleados.codigo = tb_pesadas.codigoUsuario
            WHERE fech_InicioProc = ?
                AND estadoPesada = 1
                AND tb_empleados.grupo = ?
            GROUP BY tb_pesadas.especie, tb_pesadas.codigoUsuario, nombreCompleto, tb_pesadas.fech_InicioProc
            ORDER BY tb_pesadas.codigoUsuario ASC;
            ', [$fecha, $grupo]);
    
        return response()->json($datos);
    }    

    public function traerDatosReporteSemanal(Request $request){
        $fechaDesde = $request->input('fechaDesde');
        $fechaHasta = $request->input('fechaHasta');
        $grupo = $request->input('grupo');
    
        $datos = DB::select('
            SELECT 
                tb_pesadas.fech_InicioProc,
                tb_pesadas.codigoUsuario,
                IFNULL(CONCAT_WS(" ", nombresEmpleado, apellidoPaternoEmple, apellidoMaternoEmple), "") AS nombreCompleto,
                tb_pesadas.especie,
                SUM(tb_pesadas.pesoNeto) AS totalPesoNeto,
                SUM(tb_pesadas.tara) AS totalTara,
                (
                    SELECT IFNULL(SUM(tb_descuentos.pesoDescuento), 0)
                    FROM tb_descuentos
                    WHERE tb_descuentos.codigoUsuario = tb_pesadas.codigoUsuario
                    AND tb_descuentos.especie = tb_pesadas.especie
                    AND tb_descuentos.fecha BETWEEN ? AND ?
                ) AS pesoDescuento
            FROM tb_pesadas
            INNER JOIN tb_empleados ON tb_empleados.codigo = tb_pesadas.codigoUsuario
            WHERE fech_InicioProc BETWEEN ? AND ?
                AND estadoPesada = 1
                AND tb_empleados.grupo = ?
            GROUP BY tb_pesadas.fech_InicioProc, tb_pesadas.codigoUsuario, nombreCompleto, tb_pesadas.especie
            ORDER BY tb_pesadas.codigoUsuario ASC;
        ', [$fechaDesde, $fechaHasta, $fechaDesde, $fechaHasta, $grupo]);
    
        return response()->json($datos);
    }

    public function traerProcesos(Request $request){
        $fecha = $request->input('fecha');
    
        $datos = DB::select('
            SELECT
            idProceso,
            fechaInicio,
            horainicio,
            fechaFin,
            horaFin,
            acumulado
            FROM tb_procesos
            WHERE acumulado > 0 
            AND fechaInicio = ?
            ', [$fecha]);
    
        return response()->json($datos);
    }   
    
    public function traerDetalleProceso(Request $request){
        $idProceso = $request->input('idProceso');
    
        $datos = DB::select('
            SELECT
            SUM(tb_pesadas.pesoNeto) AS totalPesoNeto,
            SUM(tb_pesadas.pesoExcedido) AS totalPesoExcedido,
            tb_pesadas.especie,
            tb_pesadas.idLote,
            tb_grupos.nombreGrupo
            FROM tb_pesadas
            INNER JOIN tb_empleados ON tb_empleados.codigo = tb_pesadas.codigoUsuario
            INNER JOIN tb_grupos ON tb_grupos.idGrupo = tb_empleados.grupo
            WHERE tb_pesadas.idProceso = ?
            GROUP BY tb_pesadas.especie, tb_pesadas.idLote, tb_grupos.nombreGrupo
            ', [$idProceso]);
    
        return response()->json($datos);
    }   
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PreciosController extends Controller
{    
    public function traerPreciosServis(){
        $datos = DB::select('
            SELECT 
            preTalloSolo,
            preTalloCoral,
            preMediaValvaTS,
            preMediaValvaTC,
            preOtros,
            tb_precio_x_presentacion.idGrupo as idGrupo,
            tb_grupos.nombreGrupo as nombreGrupo
            FROM tb_precio_x_presentacion
            INNER JOIN tb_grupos ON tb_grupos.idGrupo = tb_precio_x_presentacion.idGrupo
        ');

        return response()->json($datos);
    }

    public function guardarNuevoPrecioServis(Request $request){
        $idServis = $request->input('idServis');
        $presentacion = $request->input('presentacion');
        $nuevoPrecio = $request->input('nuevoPrecio');

        $columnas = [
            1 => 'preTalloSolo',
            2 => 'preTalloCoral',
            3 => 'preMediaValvaTS',
            4 => 'preMediaValvaTC',
            5 => 'preOtros'
        ];

        if (isset($columnas[$presentacion])) {
            $columna = $columnas[$presentacion];

            DB::update("
                UPDATE tb_precio_x_presentacion
                SET $columna = ?
                WHERE idGrupo = ?
            ", [$nuevoPrecio, $idServis]);
    
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['error' => 'Presentación no válida'], 400);
        }
    }
    
}

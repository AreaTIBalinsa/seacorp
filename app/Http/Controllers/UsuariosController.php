<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UsuariosController extends Controller
{    
    public function traerDatosUsuarios(){
        $datos = DB::select('
            SELECT 
            id,
            name,
            email
            FROM users
        ');

        return response()->json($datos);
    }
    
}

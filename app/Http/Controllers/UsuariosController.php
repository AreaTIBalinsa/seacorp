<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsuariosController extends Controller
{    
    public function traerDatosUsuarios(){
        $datos = DB::select('
            SELECT 
            id,
            name,
            email,
            (SELECT roles.name FROM model_has_roles 
                INNER JOIN roles ON roles.id = model_has_roles.role_id 
                WHERE model_has_roles.model_id = users.id 
                LIMIT 1) AS rol
            FROM users
        ');

        return response()->json($datos);
    }
    
    public function store(UserRequest $request){
        $user = User::create($request->validated());

        if ($request->filled('rol')) {
            $user->assignRole($request->input('rol'));
        }

        return redirect()->route('listarusuarios.index')->with('success', 'Usuario registrado correctamente.');
    }
    
}

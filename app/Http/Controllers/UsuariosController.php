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
            email
            FROM users
        ');

        return response()->json($datos);
    }
    public function store(UserRequest $request)
    {
        User::create($request->validated());
        $request->user()->save();
 
        return Redirect()->back();
    }
    
}

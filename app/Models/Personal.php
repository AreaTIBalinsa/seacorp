<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'tb_empleados';
    protected $primaryKey = 'idEmpleado';
    public $timestamps = false;

    // Define los atributos que son asignables en masa
    protected $fillable = [
        'idEmpleado',
        'apellidoPaternoEmple',
        'apellidoMaternoEmple',
        'nombresEmpleado',
        'tipoDocumento',
        'numDocEmple',
        'celularEmple',
        'direccionEmple',
        'estado',
        'fecha_registro',
        'hora_registro',
        'user_registro',
        'codigo',
        'grupo',
        'horaPeso',
        'controlBloq',
    ];
}

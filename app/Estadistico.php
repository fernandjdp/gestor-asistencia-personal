<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estadistico extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha', 'dia', 'empleado_id', 'hora_entra', 'hora_sale', 'estado_asistencia', 
    ];
}

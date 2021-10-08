<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario', 'clasificacion_reporte', 'tipo_reporte', 'amplitud_reporte', 'ordenar_por', 'fecha_inicial', 'fecha_final'
    ];
}


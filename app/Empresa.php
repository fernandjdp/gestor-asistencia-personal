<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion_empresa','logo', 'subtitulo_reporte'
    ];
    protected $casts = [
    	'logo' => 'json'
    ];

}

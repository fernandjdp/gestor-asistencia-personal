<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CargarRegistros extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'texto_registro', 'registrado_por', 'nombre_texto'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion_tipo_permiso'
    ];
}

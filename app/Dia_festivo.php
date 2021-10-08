<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dia_festivo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion_dia_festivo', 'fecha', 'tipo'
    ];
}

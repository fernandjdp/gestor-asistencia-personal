<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion_nomina'
    ];
}

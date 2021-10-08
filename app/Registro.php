<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dev_id', 'description', 'model', 'empleado_id', 'username', 'datetime', 'time', 'in_out', 'workcode', 'department', 'emp_num' 
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion_horario', 'dias_laborables','dias_no_laborables','dias_operativos','dias_hrs', 'hora_entrada', 'hora_salida', 'inicio_descanso', 'fin_descanso', 'tolerancia_entrada_antes', 'tolerancia_entrada_despues','tolerancia_salida_horas_extra', 'tolerancia_descanso_fin'
    ];
}

// dias_laborables = Array de 7 posiciones [x,x,x,x,x,x,x] que determina con valores del 0 al 3 según sea el día correspondiente No Laborable, Laborable, Operativo u Hrs

// tolerancia_entrada_antes = Cuanto tiempo antes de la hora de entrada es permitido marcar sin faltas [Lo mismo para la tolerancia despues]

// tolerancia_descanso_fin = Cuanto tiempo DESPUES de la hora de descanso puedes marcar sin recibir amonestaciones

// tolerancia_salida_horas_extra = A partir de cuanto tiempo DESPUES de la hora de salida de trabajo se contará como horas extra trabajadas
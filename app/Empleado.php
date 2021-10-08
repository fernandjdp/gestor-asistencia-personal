<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_completo', 'cedula', 'biometrico_id', 'nacionalidad', 'estado_civil', 'estado_nacimiento', 'lugar_nacimiento', 'instruccion_academica', 'profesion', 'telefono_fijo', 'telefono_movil', 'correo', 'sexo', 'condicion_empleado', 'empresas_id', 'cargos_id', 'departamentos_id', 'tipo_empleados_id', 'tipo_nominas_id', 'horarios_id','jornada_laboral', 'horas_minimas_cesta_ticket', 'valor_ticket', 'codigo_punto_venta', 'prorratear_cesta_ticket','pago_horas_extra_antes', 'pago_horas_extra_despues', 'marcar_entrada_salida', 'inicio_salida_nocturna', 'fin_salida_nocturna', 'salida_nocturna', 'descontar_horas_descanso', 'marca_rango_comida', 'duracion_rango_comida', 'cesta_sabado_domingo_rango', 'duracion_rango_cesta', 'confianza' 
    ];
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            /*DATOS PERSONALES*/
            $table->string('nombre_completo');
            $table->string('cedula');
            $table->string('biometrico_id');
            $table->string('nacionalidad');
            $table->string('estado_civil');
            $table->string('estado_nacimiento');
            $table->string('lugar_nacimiento');
            $table->string('instruccion_academica');
            $table->string('profesion');
            $table->string('telefono_fijo');
            $table->string('telefono_movil');
            $table->string('correo');
            $table->string('sexo');
            $table->boolean('condicion_empleado');
            /*DATOS DE NOMINA*/
            $table->unsignedInteger('empresas_id');
            //$table->foreign('empresas_id')->references('id')->on('empresas');
            $table->unsignedInteger('cargos_id');
            //$table->foreign('cargos_id')->references('id')->on('cargos');
            $table->unsignedInteger('departamentos_id');
            //$table->foreign('departamentos_id')->references('id')->on('departamentos');
            $table->unsignedInteger('tipo_empleados_id');
            //$table->foreign('tipo_empleados_id')->references('id')->on('tipo_empleados');
            $table->unsignedInteger('tipo_nominas_id');
            //$table->foreign('tipo_nominas_id')->references('id')->on('nominas');
            /*HORARIOS*/
            $table->unsignedInteger('horarios_id');
            //$table->foreign('horarios_id')->references('id')->on('horarios');
            /*CESTA TICKET*/
            $table->integer('jornada_laboral');
            $table->integer('horas_minimas_cesta_ticket');
            $table->float('valor_ticket');
            $table->string('codigo_punto_venta');
            $table->boolean('prorratear_cesta_ticket');
            /*ESPECIALES*/
            $table->boolean('pago_horas_extra_antes');
            $table->boolean('pago_horas_extra_despues');
            $table->boolean('marcar_entrada_salida');
            $table->boolean('salida_nocturna');
            $table->time('inicio_salida_nocturna');
            $table->time('fin_salida_nocturna');
            $table->boolean('descontar_horas_descanso');
            $table->boolean('marca_rango_comida');
            $table->integer('duracion_rango_comida');
            $table->boolean('cesta_sabado_domingo_rango');
            $table->integer('duracion_rango_cesta');
            $table->boolean('confianza')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('empleados');
        Schema::enableForeignKeyConstraints();
    }
}   

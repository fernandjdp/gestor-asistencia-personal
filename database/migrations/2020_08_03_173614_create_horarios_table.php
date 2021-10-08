<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion_horario');
            $table->string('dias_laborables')->nullable();
            $table->string('dias_no_laborables')->nullable();
            $table->string('dias_operativos')->nullable();
            $table->string('dias_hrs')->nullable();
            $table->time('hora_entrada', 0);
            $table->time('hora_salida', 0);
            $table->time('inicio_descanso', 0);
            $table->time('fin_descanso', 0);
            $table->time('tolerancia_entrada_antes', 0);
            $table->time('tolerancia_entrada_despues', 0);
            $table->time('tolerancia_salida_horas_extra', 0);
            $table->time('tolerancia_descanso_fin', 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios');
    }
}

/* 

'descripcion_horario', 'dias_laborables', 'hora_entrada', 'hora_salida', 'inicio_descanso', 'fin_descanso', 'tolerancia_entrada_antes', 'tolerancia_entrada_despues','tolerancia_salida_horas_extra', 'tolerancia_descanso_fin'

*/
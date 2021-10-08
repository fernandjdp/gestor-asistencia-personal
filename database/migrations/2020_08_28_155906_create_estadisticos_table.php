<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadisticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estadisticos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empleado_id');
            $table->date('fecha');
            $table->string('dia'); 
            $table->string('estado_asistencia'); // Cambiarlo a string 
            $table->time('hora_entra')->nullable();
            $table->time('hora_sale')->nullable();
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
        Schema::dropIfExists('estadisticos');
    }
}

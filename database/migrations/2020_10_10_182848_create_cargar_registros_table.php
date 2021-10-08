<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargarRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargar_registros', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('texto_registro');
            $table->string('nombre_texto');
            $table->string('registrado_por');
            $table->date('fecha_inicial');
            $table->date('fecha_final');
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
        Schema::dropIfExists('cargar_registros');
    }
}

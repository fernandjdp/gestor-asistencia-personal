<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroRespaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_respaldos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dev_id');
            $table->string('description');
            $table->string('model');
            $table->integer('empleado_id');
            $table->string('username')->nullable();
            $table->date('date',0);
            $table->time('time',0);
            $table->string('in_out');
            $table->string('workcode')->nullable();
            $table->string('department')->nullable();
            $table->string('emp_num')->nullable();
            $table->date('fecha_registro')->nullable();
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
        Schema::dropIfExists('registro_respaldos');
    }
}

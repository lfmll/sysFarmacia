<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBitacorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->string('accion');   //Crear, Actualizar, Eliminar, Ejecutar
            $table->string('descripcion')->nullable(); //Descripcion de la accion
            $table->string('modulo')->nullable();   //Nombre del modulo
            $table->dateTime('fecha_hora');
            $table->string( 'ip')->nullable(); //IP del usuario que realiza la accion
            $table->string('user_id'); //Usuario que realiza la accion
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
        Schema::dropIfExists('bitacoras');
    }
}

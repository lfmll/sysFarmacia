<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuis', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo_cuis',60);         
            $table->datetime('fecha_vigencia');
            $table->char('estado',1);
            $table->smallInteger('agencia_id')->unsigned();
            $table->smallInteger('punto_venta_id')->unsigned();
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
        Schema::dropIfExists('cuis');
    }
}

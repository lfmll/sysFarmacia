<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCufdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cufds', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo_cufd',100);  
            $table->string('codigo_control',25);
            $table->string('direccion',100);    
            $table->datetime('fecha_vigencia');
            $table->char('estado',1);
            $table->smallInteger('cuis_id')->unsigned();
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
        Schema::dropIfExists('cufds');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agencias', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo',5);
            $table->string('nombre',50);
            $table->string('departamento',50);            
            $table->string('municipio',50);
            $table->string('direccion');
            $table->string('telefono',10);            
            $table->char('estado',1);
            $table->smallinteger('empresa_id')->unsigned();
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
        Schema::dropIfExists('agencias');
    }
}

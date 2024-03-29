<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre',50);                       
            $table->string('nit',25);
            $table->string('correo',50);
            $table->string('actividad',50);
            $table->string('documento',50);
            $table->string('modalidad',50);
            $table->string('emision',50);
            $table->string('cuis',50);
            $table->date('vigencia_cuis');
            $table->string('extension',5)->nullable();
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
        Schema::dropIfExists('empresas');
    }
}

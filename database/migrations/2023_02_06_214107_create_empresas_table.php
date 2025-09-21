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
            $table->string('extension',5)->nullable();
            $table->string('sistema',50)->nullable();
            $table->string('codigo_sistema',25)->nullable();
            $table->string('version',10)->nullable();
            $table->char('modalidad',1)->nullable();
            $table->char('ambiente',1)->nullable();
            $table->char('estado',1)->nulllable();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger('tipo_documento');
            $table->string('numero_documento',30);
            $table->string('complemento',30)->nullable();
            $table->string('nombre');
            $table->string('correo');
            $table->string('telefono',25)->nullable();
            $table->string('direccion')->nullable();
            $table->char('estado',1);
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
        Schema::dropIfExists('clientes');
    }
}

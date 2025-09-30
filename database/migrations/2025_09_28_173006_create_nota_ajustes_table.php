<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotaAjustesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_ajustes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('tipo'); //credito o debito
            $table->string('estado'); //enviado, aceptado, rechazado
            $table->string('monto');
            $table->string('motivo');
            $table->string('codigoPuntoVenta');
            $table->string('codigoDocumentoSector');
            $table->string('fechaEmision');
            $table->string('usuario');
            $table->smallInteger('factura_id')->unsigned();
            $table->smallInteger('cufd_id')->unsigned();
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
        Schema::dropIfExists('nota_ajustes');
    }
}

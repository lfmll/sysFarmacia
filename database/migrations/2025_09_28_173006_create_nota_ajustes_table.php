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
            $table->string('numeroNotaCreditoDebito');
            $table->string('tipo'); //credito o debito
            $table->string('estado'); //enviado, aceptado, rechazado
            $table->string('motivo');
            $table->string('tipoFactura'); //tipo factura documento ajuste
            $table->string('codigoDocumentoSector');
            $table->string('fechaEmision');
            $table->string('montoTotal');
            $table->string('cuf');
            $table->string('montoTotalDevuelto');
            $table->string('montoDescuentoCreditoDebito');
            $table->string('montoEfectivoCreditoDebito');
            $table->string('codigoExcepcion')->nullable();
            $table->string('codigoRecepcion')->nullable();
            $table->string('leyenda');
            $table->smallInteger('factura_id')->unsigned();
            $table->smallInteger('cufd_id')->unsigned();
            $table->smallInteger('punto_venta_id')->unsigned();
            $table->unsignedBigInteger('user_id');
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

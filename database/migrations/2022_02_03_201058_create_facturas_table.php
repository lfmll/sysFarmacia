<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nroFactura'); 
            $table->string('nroAutorizacion');
            $table->datetime('fecha');
            $table->string('NIT');
            $table->string('razonSocial');
            $table->date('fechaLimite');
            $table->string('codigoControl');
            $table->decimal('total',12,2);
            $table->string('totalLiteral');
            $table->char('eliminado');
            $table->smallInteger('venta_id')->unsigned();
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
        Schema::dropIfExists('facturas');
    }
}

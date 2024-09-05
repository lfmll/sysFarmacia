<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_facturas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('actividadEconomica');
            $table->string('codigoProductoSin');
            $table->string('codigoProducto');
            $table->string('descripcion');
            $table->string('cantidad');
            $table->string('unidadMedida');
            $table->string('precioUnitario');
            $table->string('montoDescuento');
            $table->string('subTotal');
            $table->string('numeroSerie');
            $table->string('numeroImei');

            $table->smallInteger('factura_id')->unsigned();
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
        Schema::dropIfExists('detalle_facturas');
    }
}

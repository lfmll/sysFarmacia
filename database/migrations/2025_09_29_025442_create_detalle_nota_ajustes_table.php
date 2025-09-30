<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleNotaAjustesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_nota_ajustes', function (Blueprint $table) {
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
            $table->string('numeroSerie')->nullable();
            $table->string('numeroImei')->nullable();
            $table->smallInteger('nota_ajuste_id')->unsigned();
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
        Schema::dropIfExists('detalle_nota_ajustes');
    }
}

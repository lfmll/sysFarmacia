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
            $table->string('nitEmisor'); 
            $table->string('razonSocialEmisor');
            $table->string('municipio');
            $table->string('telefono');
            $table->string('numeroFactura');
            $table->string('cuf')->nullable();
            $table->string('cufd')->nullable();
            $table->string('codigoSucursal');
            $table->string('direccion');
            $table->string('codigoPuntoVenta')->nullable();
            $table->string('fechaEmision');
            $table->string('nombreRazonSocial');
            $table->string('codigoTipoDocumentoIdentidad');
            $table->string('numeroDocumento');
            $table->string('complemento')->nullable();
            $table->string('codigoCliente');
            $table->string('codigoMetodoPago');
            $table->string('numeroTarjeta')->nullable();
            $table->string('montoTotal');
            $table->string('montoTotalSujetoIva');
            $table->string('codigoMoneda');
            $table->string('tipoCambio');
            $table->string('montoTotalMoneda');
            $table->string('montoGiftCard')->nullable();
            $table->string('descuentoAdicional');
            $table->string('codigoExcepcion')->nullable();
            $table->string('cafc')->nullable();
            $table->string('leyenda');
            $table->string('usuario');
            $table->string('codigoDocumentoSector');
            $table->string('estado');
            $table->string('codigoRecepcion')->nullable();
            
            $table->smallInteger('venta_id')->unsigned();
            $table->smallInteger('evento_id')->unsigned()->nullable();
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

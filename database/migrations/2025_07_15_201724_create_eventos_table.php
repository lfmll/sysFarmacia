<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigoAmbiente');
            $table->string('codigoSistema');
            $table->string('nit');
            $table->string('cuis');
            $table->string('cufd');
            $table->string('codigoSucursal');
            $table->string('codigoPuntoVenta');
            $table->string('codigoEvento');
            $table->string('descripcion');
            $table->string('estado');
            $table->string('fechaInicioEvento');
            $table->string('fechaFinEvento')->nullable();
            $table->string('cufdEvento');
            $table->integer('cantidadFacturas')->default(0);
            $table->string('codigoDocumentoSector')->nullable();
            $table->string('cafc')->nullable();
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
        Schema::dropIfExists('eventos');
    }
}

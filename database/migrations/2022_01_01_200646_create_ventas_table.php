<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('comprobante',15)->nullable();
            $table->datetime('fecha_venta');
            $table->decimal('pago_venta',10,2);
            $table->decimal('cambio_venta',10,2);    
            $table->string('glosa')->nullable();
            $table->string('forma_pago');   
            $table->char('estado');
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
        Schema::dropIfExists('ventas');
    }
}

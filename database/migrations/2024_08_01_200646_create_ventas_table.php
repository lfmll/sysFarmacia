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
            $table->datetime('fecha_venta');
            $table->decimal('subtotal',10,2);
            $table->decimal('descuento',10,2);                      
            $table->decimal('total',10,2);    
            $table->decimal('monto_giftcard',10,2)->nullable();
            $table->string('numero_tarjeta')->nullable();
            $table->decimal('monto_pagar',10,2);
            $table->decimal('importe_iva',10,2);
            $table->decimal('cambio_venta',10,2);
            $table->string('literal');
            $table->char('estado');
            $table->smallInteger('cliente_id')->unsigned();
            $table->smallInteger('user_id')->unsigned();        
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

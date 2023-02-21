<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('comprobante',15)->nullable();
            $table->datetime('fecha_compra');
            $table->smallInteger('agente_id')->unsigned()->nullable();
            $table->decimal('pago_compra',10,2);
            $table->decimal('cambio_compra',10,2);
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
        Schema::dropIfExists('compras');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre',50);
            $table->integer('stock');
            $table->integer('stock_minimo');
            $table->date('fecha_vencimiento');
            $table->decimal('precio_compra',8,2)->nullable();
            $table->decimal('precio_venta',8,2)->nullable();
            $table->tinyInteger('ganancia');
            $table->char('estado',1);
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
        Schema::dropIfExists('productos');
    }
}

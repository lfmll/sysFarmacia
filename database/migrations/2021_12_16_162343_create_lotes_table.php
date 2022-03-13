<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('numero');
            $table->integer('cantidad');
            $table->date('fecha_vencimiento');
            $table->smallInteger('laboratorio_id')->unsigned()->nullable();
            $table->smallInteger('medicamento_id')->unsigned()->nullable();
            $table->smallInteger('insumo_id')->unsigned()->nullable();
            $table->smallInteger('producto_id')->unsigned()->nullable();
            $table->decimal('precio_compra',8,2)->nullable();
            $table->decimal('precio_venta',8,2)->nullable();
            $table->decimal('ganancia',8,2);
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
        Schema::dropIfExists('lotes');
    }
}

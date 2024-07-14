<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo_actividad',10);
            $table->string('codigo_producto',10);
            $table->string('codigo_producto_sin',10);
            $table->string('nombre_comercial',50);
            $table->string('nombre_generico',50);
            $table->string('composicion')->nullable();
            $table->string('indicacion')->nullable();
            $table->string('contraindicacion')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('stock')->unsigned();
            $table->integer('stock_minimo')->unsigned();
            $table->smallInteger('codigo_clasificador');
            $table->smallInteger('via_id')->unsigned();
            
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
        Schema::dropIfExists('medicamentos');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSincronizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sincronizaciones', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->timestamp('fecha_sincronizada')->nullable();
            $table->timestamp('fecha_local')->nullable();
            $table->string('diferencia_horaria',10)->nullable();
            $table->string('nit');
            $table->smallInteger('agencia_id')->unsigned();
            $table->smallInteger('punto_venta_id')->unsigned();
            $table->smallInteger('cuis_id')->unsigned();
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
        Schema::dropIfExists('sincronizaciones');
    }
}

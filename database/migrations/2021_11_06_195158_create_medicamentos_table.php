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
            $table->string('nombre_comercial',50);
            $table->string('nombre_generico',50);
            $table->string('composicion',255)->nullable();
            $table->string('indicacion',255)->nullable();
            $table->string('contradicacion',255)->nullable();
            $table->integer('stock')->unsigned();
            $table->integer('stock_minimo')->unsigned();
            $table->smallinteger('id_formato')->unsigned();
            $table->smallinteger('id_laboratorio')->unsigned();
            $table->smallinteger('id_via')->unsigned();
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

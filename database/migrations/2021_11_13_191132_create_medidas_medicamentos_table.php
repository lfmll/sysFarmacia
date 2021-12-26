<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedidasMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidas_medicamentos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('descripcion','50');
            $table->string('dosis_estandar')->nullable();
            $table->smallInteger('medicamento_id')->unsigned();
            $table->smallInteger('medida_id')->unsigned();            
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
        Schema::dropIfExists('medidas_medicamentos');
    }
}

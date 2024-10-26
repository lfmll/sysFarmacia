<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_documentos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo_actividad',10);
            $table->string('codigo_documento_sector',5);
            $table->string('tipo_documento_sector',25);
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
        Schema::dropIfExists('actividad_documentos');
    }
}

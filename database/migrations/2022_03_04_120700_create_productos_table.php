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
            $table->string('codigo',50);
            $table->string('descripcion');
            $table->decimal('precio_unitario',10,2);
            $table->string('unidad',50);
            $table->integer('catalogo_id')->unsigned();
            $table->string('extension',5)->nullable();   
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

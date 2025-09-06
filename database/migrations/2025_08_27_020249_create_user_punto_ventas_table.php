<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPuntoVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_punto_ventas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->smallInteger('punto_venta_id')->unsigned();
            $table->char('estado',1);
            $table->string('fecha_asignacion');
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
        Schema::dropIfExists('user_punto_venta');
    }
}

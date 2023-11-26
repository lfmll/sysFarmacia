<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntoVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punto_ventas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('descripcion',50);
            $table->smallinteger('agencia_id')->unsigned();
            $table->smallinteger('user_id')->unsigned();
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
        Schema::dropIfExists('punto_ventas');
    }
}

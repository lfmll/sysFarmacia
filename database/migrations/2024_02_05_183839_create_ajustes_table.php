<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAjustesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajustes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('driver',5)->nullable();
            $table->string('host',5)->nullable();
            $table->string('port',5)->nullable();
            $table->string('encryption',10)->nullable();
            $table->string('username',50);
            $table->string('password',50)->nullable();
            $table->string('from',50)->nullable();
            $table->string('name',50)->nullable();
            $table->string('token',500)->nullable();
            $table->string('wsdl',50)->nullable();
            $table->smallInteger('punto_venta_id')->unsigned();
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
        Schema::dropIfExists('ajustes');
    }
}

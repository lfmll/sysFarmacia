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
            $table->string('cuis',50);
            $table->datetime('fecha_cuis');
            $table->string('cuifd',50);
            $table->datetime('fecha_cuifd');
            $table->string('driver',5);
            $table->string('host',5);
            $table->string('port',5);
            $table->string('encryption',10);
            $table->string('username',50);
            $table->string('password',50);
            $table->string('from',50);
            $table->string('name',50)->nullable();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->decimal('monto_apertura',12,2);
            $table->date('fecha');
            $table->time('hora_inicio');            
            $table->time('hora_fin')->nullable();
            $table->integer('b200')->nullable();
            $table->integer('b100')->nullable();
            $table->integer('b50')->nullable();
            $table->integer('b20')->nullable();
            $table->integer('b10')->nullable();
            $table->integer('m5')->nullable();
            $table->integer('m2')->nullable();
            $table->integer('m1')->nullable();
            $table->integer('m05')->nullable();
            $table->integer('m02')->nullable();
            $table->integer('m01')->nullable();
            $table->decimal('gastos',12,2)->nullable();
            $table->decimal('ganancias',12,2)->nullable();
            $table->decimal('total',12,2)->nullable();
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
        Schema::dropIfExists('cajas');
    }
}

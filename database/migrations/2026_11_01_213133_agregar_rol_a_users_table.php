<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AgregarRolAUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
            $table->string('rol', 10)->default('cajero')->after('password');
            });

            // El usuario más antiguo (el primero que se registró) pasa a ser administrador.
            $primero = DB::table('users')->min('id');
            if ($primero) {
                DB::table('users')->where('id', $primero)->update(['rol' => 'admin']);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('rol');
            });
        });
    }
}

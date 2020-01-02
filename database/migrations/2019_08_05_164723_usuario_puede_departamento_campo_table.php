<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsuarioPuedeDepartamentoCampoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_puede_departamento_campo', function (Blueprint $table) {
            $table->increments('id');
            //  Clave ajena a la table de usuarios
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            //  Clave ajenta a la tabla campo_departamento que indica los departamentos que tiene un campo
            $table->unsignedInteger('campo_departamento_id');
            $table->foreign('campo_departamento_id')->references('id')->on('campo_departamento')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_puede_departamento_campo');
    }
}

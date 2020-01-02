<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDireccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('calle', 255);
            $table->string('ciudad');
            $table->string('codigo_postal');
            $table->string('provincia');
            $table->string('pais');
            $table->string('persona_contacto');
            $table->string('numero_contacto');
            //  Relación con la tabla campos
            $table->unsignedInteger('campo_id');
            $table->foreign('campo_id')->references('id')->on('campos');
            $table->boolean('activo')->default(true);
            
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
        Schema::dropIfExists('direcciones');
    }
}

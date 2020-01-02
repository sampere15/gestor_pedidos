<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCamposSociedades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campo_sociedad', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campo_id');
            $table->foreign('campo_id')->references('id')->on('campos');
            $table->unsignedInteger('sociedad_id');
            $table->foreign('sociedad_id')->references('id')->on('sociedades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campo_sociedad');
    }
}

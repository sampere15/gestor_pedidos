<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescripcionsTable extends Migration
{
    //  Migracion no utilizada
    // /**
    //  * Run the migrations.
    //  *
    //  * @return void
    //  */
    // public function up()
    // {
    //     Schema::create('descripciones', function (Blueprint $table) {
    //         $table->increments('id');
    //         $table->string('nombre');
    //         //  Relación con la tabla categorias
    //         $table->unsignedInteger('categoria_id');
    //         $table->foreign('categoria_id')->references('id')->on('categorias');
            
    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('descripciones');
    // }
}

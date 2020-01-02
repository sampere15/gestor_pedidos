<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RelationCampoSociedadFavorita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campos', function (BLueprint $table){
            //  Relacion con Sociedad para indicar cual es su "favorita" o sociedad por defecto
            $table->unsignedInteger('sociedad_favorita_id')->nullable();
            $table->foreign('sociedad_favorita_id')->references('id')->on('sociedades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campos', function (BLueprint $table){
            $table->dropForeign(['sociedad_favorita_id']);
            $table->dropColumn('sociedad_favorita_id');
        });
    }
}

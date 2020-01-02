<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RelationUserCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  El usuario ya no tiene una relación directa con el campo, si no con la relación campo_departamento
        // Schema::table('users', function (BLueprint $table){
        //     //  Un usuario pertenece a un campo
        //     $table->unsignedInteger('campo_id')->after('password');
        //     $table->foreign('campo_id')->references('id')->on('campos');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //  El usuario ya no tiene una relación directa con el campo, si no con la relación campo_departamento
        // Schema::table('users', function (BLueprint $table){
        //     $table->dropForeign(['campo_id']);
        //     $table->dropColumn('campo_id');
        // });
    }
}

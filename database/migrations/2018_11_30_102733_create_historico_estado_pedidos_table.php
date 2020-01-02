<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricoEstadoPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_estados_pedidos', function (Blueprint $table) {
            $table->increments('id');

            //  Relación con la tabla pedidos
            $table->unsignedInteger('pedido_id');
            $table->foreign('pedido_id')->references('id')->on('pedidos');

            //  Grabamos a fuego el nombre del estado, me gusta más para el tema de históricos, así no se verá modificado si cambian los estados en un futuro
            $table->string('estado');

            //  Relación con el usuario que ha "probocado" dicho estado
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');

            $table->timestamp('fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historico_estados_pedidos');
    }
}

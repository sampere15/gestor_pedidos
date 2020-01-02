<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');

            //  Fecha en la que va a contar/computar el pedido. También se utilizará para los informes
            $table->timestamp('fecha_pedido')->nullable()->default(NULL);

            //  Relación con el usuario que realiza el pedido
            $table->unsignedInteger('usuario_realiza_pedido_id');
            $table->foreign('usuario_realiza_pedido_id')->references('id')->on('users');

            //  El pedido se le hace a un proveedor
            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');

            //  El pedido es para un campo
            $table->unsignedInteger('campo_id');
            $table->foreign('campo_id')->references('id')->on('campos');

            //  Dentro de un campo será para un departamento
            $table->unsignedInteger('departamento_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos');

            //  El pedido va a una sociedad propietaria/gestora, etc.
            $table->unsignedInteger('sociedad_id');
            $table->foreign('sociedad_id')->references('id')->on('sociedades');

            //  El total del pedido, mejor tenerlo guardado y a mano en cualquier momento que tener que calcular el sumatorio de lineas
            $table->float('total_pedido');

            //  Control para ver si el documento ha sido visualizado. Hasta que no se visualice no permitimos que se comunique al proveedor
            $table->boolean('documento_visualizado')->default(false);

            //  El pedido aunque sea para un campo puede tener diferentes direcciones de entrega o personas a las que entregarse
            $table->unsignedInteger('direccion_id');
            $table->foreign('direccion_id')->references('id')->on('direcciones');

            //  El pedido puede tener diferentes estados
            $table->unsignedInteger('estado_pedido_id')->nullable();
            $table->foreign('estado_pedido_id')->references('id')->on('estados_pedidos');

            //  Para indicar que Virginia le ha enviado el pedido al proveedor
            $table->boolean('pedido_comunicado')->default(false);

            //  Para indicar si el pedido ha sido comunicado al proveedor. CUando alguien llama para solicita por ejemplo una rueda que tarda 15 días, para que vaya saliendo....
            $table->boolean('solicitado_al_proveedor')->default(false);

            //  Para indicar si el pedido ha sido previamente recepcionado por el técnico. O ha ido directamente a comprarlo y se lo ha traido
            $table->boolean('ya_recibido')->default(false);

            //  Para indicar si un pedido ha sido cancelado, borrado, etc
            $table->boolean('cancelado')->default(false);
            $table->string('motivo_cancelacion')->nullable();

            //  Las observaciones que podrá incluir desde quien crear el pedido hasta quien lo cursa pasando por quien lo valida
            $table->string('observaciones')->nullable();

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
        Schema::dropIfExists('pedidos');
    }
}

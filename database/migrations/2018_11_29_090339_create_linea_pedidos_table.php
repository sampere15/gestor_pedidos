<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Categoria;
use App\EstadoLineaPedido;

class CreateLineaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineas_pedidos', function (Blueprint $table) {
            $table->increments('id');

            //  Una línea de pedido pertenece sí o sí a un pedido
            $table->unsignedInteger('pedido_id');
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            
            $table->unsignedInteger('numero_linea');

            $table->string('descripcion');
            $table->float('unidades');
            $table->float('precio');

            //  La línea de pedido debe de pertenecer a una categoría
            $table->unsignedInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categorias');

            //  Lo que pedimos en sí debe de tener un formato (kilos, metros, horas, etc)
            $table->unsignedInteger('formato_id');
            $table->foreign('formato_id')->references('id')->on('formatos');

            //  La línea puede tener diferentes estados
            $table->unsignedInteger('estado_linea_id')->default(1);
            $table->foreign('estado_linea_id')->references('id')->on('estados_lineas');

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
        Schema::dropIfExists('lineas_pedidos');
    }
}

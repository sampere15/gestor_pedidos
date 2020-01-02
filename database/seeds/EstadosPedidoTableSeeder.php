<?php

use Illuminate\Database\Seeder;
use App\EstadoPedido;

class EstadosPedidosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoPedido::create([
            'nombre' => 'en_creacion', 
            'descripcion' => 'Pedido que está en creación, pendiente de finalizar. El usuario debe confirmar el pedido cuando esté listo', 
            'nombre_mostrar' => 'En creación', 
            'color' => '#DDDDDD']);

        EstadoPedido::create([
            'nombre' => 'solicitado',
            'descripcion' => 'Pedido completado por el usuario',
            'nombre_mostrar' => 'Solicitado',
            'color' => '#B7DEE8']);

        EstadoPedido::create([
            'nombre' => 'validado',
            'descripcion' => 'Pedido validado',
            'nombre_mostrar' => 'Validado',
            'color' => '#93CDDD']);

        EstadoPedido::create([
            'nombre' => 'cursado',
            'descripcion' => 'Pedido aprobado para su envío al proveedor',
            'nombre_mostrar' => 'Cursado',
            'color' => '#4BACC6']);

        EstadoPedido::create([
            'nombre' => 'pendiente_recibir',
            'descripcion' => 'Pedido que ya se ha comunicado al proveedor',
            'nombre_mostrar' => 'Pendiente de recibir',
            'color' => '#31859C']);

        EstadoPedido::create([
            'nombre' => 'recibido_parcialmente',
            'descripcion' => 'Recibido parcialmente',
            'nombre_mostrar' => 'Recibido parcialmente',
            'color' => '#FF9900']);

        EstadoPedido::create([
            'nombre' => 'finalizado',
            'descripcion' => 'Pedido finalizado',
            'nombre_mostrar' => 'Recibido',
            'color' => '#008000']);


        //  Estados de cancelación
        //  Se borra - on delete cascade pero programado
        //  cancelado
        //  rechazado
    }
}

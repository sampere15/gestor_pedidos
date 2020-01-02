<?php

use Illuminate\Database\Seeder;
use App\HistoricoEstadoPedido;
use App\Pedido;
use App\EstadoPedido;

class HistoricosEstadosPedidosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// $pedido = Pedido::with('estadoPedido', 'usuarioRealizaPedido')->first();
        
     //    HistoricoEstadoPedido::create([
     //    	'pedido_id' => $pedido->id,
     //    	'estado' => $pedido->estadoPedido->nombre,
     //    	'usuario_id' => $pedido->usuarioRealizaPedido->id,
     //    	'fecha' => date("Y-m-d H:i:s"),
     //    ]);
    }
}

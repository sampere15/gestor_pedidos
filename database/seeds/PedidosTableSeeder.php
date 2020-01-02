<?php

use Illuminate\Database\Seeder;
use App\Pedido;
use App\User;
use App\Proveedor;
use App\Campo;
use App\Sociedad;
use App\Direccion;
use App\EstadoPedido;

class PedidosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::first();
        // $proveedor = Proveedor::first();
        // $campo = $user->campo;
        // $sociedad = Sociedad::first();
        // $direccion = Direccion::first();
        // $estadoPedido = EstadoPedido::where('nombre', 'solicitado')->firstOrFail();

        // Pedido::create([
        // 	'usuario_realiza_pedido_id' => $user->id,
        // 	'proveedor_id' => $proveedor->id,
        // 	'campo_id' => $campo->id,
        //     'total_pedido' => "12",
        // 	'sociedad_id' => $sociedad->id,
        // 	'direccion_id' => $direccion->id,
        // 	'estado_pedido_id' => $estadoPedido->id,
        // ]);

        // Pedido::create([
        //     'usuario_realiza_pedido_id' => $user->id,
        //     'proveedor_id' => $proveedor->id,
        //     'campo_id' => $campo->id,
        //     'sociedad_id' => $sociedad->id,
        //     'direccion_id' => $direccion->id,
        //     'estado_pedido_id' => $estadoPedido->id + 1,
        // ]);

        // Pedido::create([
        //     'usuario_realiza_pedido_id' => $user->id,
        //     'proveedor_id' => $proveedor->id,
        //     'campo_id' => $campo->id,
        //     'sociedad_id' => $sociedad->id,
        //     'direccion_id' => $direccion->id,
        //     'estado_pedido_id' => $estadoPedido->id + 2,
        // ]);
    }
}

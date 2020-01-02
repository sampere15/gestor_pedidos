<?php

use Illuminate\Database\Seeder;
use App\Pedido;
use App\EstadoLinea;
use App\LineaPedido;
use App\Categoria;
use App\Formato;

class LineasPedidosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $pedido = Pedido::first();
        // $estadoLinea = EstadoLinea::where('nombre', 'pendiente')->first();
        // $categoria = Categoria::first();
        // $formato = Formato::where('nombre', 'unidad')->first();

        // LineaPedido::create([
        // 	'pedido_id' => $pedido->id,
        // 	'estado_linea_id' => $estadoLinea->id,
        // 	'numero_linea' => '1',
        // 	'descripcion' => 'Vasos plástico',
        // 	'unidades' => '1000',
        // 	'precio' => '0.01',
        // 	'categoria_id' => $categoria->id,
        // 	'formato_id' => $formato->id
        // ]);

        // LineaPedido::create([
        //     'pedido_id' => $pedido->id,
        //     'estado_linea_id' => $estadoLinea->id,
        //     'numero_linea' => '2',
        //     'descripcion' => 'Pesticida',
        //     'unidades' => '5',
        //     'precio' => '0.4',
        //     'categoria_id' => $categoria->id,
        //     'formato_id' => $formato->id
        // ]);

        // LineaPedido::create([
        //     'pedido_id' => $pedido->id + 1,
        //     'estado_linea_id' => $estadoLinea->id,
        //     'numero_linea' => '1',
        //     'descripcion' => 'Vasos plástico',
        //     'unidades' => '1000',
        //     'precio' => '0.01',
        //     'categoria_id' => $categoria->id,
        //     'formato_id' => $formato->id
        // ]);

        // LineaPedido::create([
        //     'pedido_id' => $pedido->id + 1,
        //     'estado_linea_id' => $estadoLinea->id,
        //     'numero_linea' => '2',
        //     'descripcion' => 'Pesticida',
        //     'unidades' => '5',
        //     'precio' => '0.4',
        //     'categoria_id' => $categoria->id,
        //     'formato_id' => $formato->id
        // ]);

        // LineaPedido::create([
        //     'pedido_id' => $pedido->id + 2,
        //     'estado_linea_id' => $estadoLinea->id,
        //     'numero_linea' => '1',
        //     'descripcion' => 'Vasos plástico',
        //     'unidades' => '1000',
        //     'precio' => '0.01',
        //     'categoria_id' => $categoria->id,
        //     'formato_id' => $formato->id
        // ]);

        // LineaPedido::create([
        //     'pedido_id' => $pedido->id + 2,
        //     'estado_linea_id' => $estadoLinea->id,
        //     'numero_linea' => '2',
        //     'descripcion' => 'Pesticida',
        //     'unidades' => '5',
        //     'precio' => '0.4',
        //     'categoria_id' => $categoria->id,
        //     'formato_id' => $formato->id
        // ]);
    }
}

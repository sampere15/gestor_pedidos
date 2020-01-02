<?php

use Illuminate\Database\Seeder;
use App\EstadoLinea;

class EstadosLineaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoLinea::create([
        	'nombre' => 'pendiente',
        	'descripcion' => 'Línea de pedido pendiente de recibir',
        	'nombre_mostrar' => 'Pendiente',
        ]);

        EstadoLinea::create([
        	'nombre' => 'parcial',
        	'descripcion' => 'Línea de pedido recibida parcialmente',
        	'nombre_mostrar' => 'Línea recibida parcialmente',
        ]);

        EstadoLinea::create([
        	'nombre' => 'recibida',
        	'descripcion' => 'Línea de pedido recibida',
        	'nombre_mostrar' => 'Línea recibida',
        ]);
    }
}

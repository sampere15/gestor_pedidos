<?php

use Illuminate\Database\Seeder;
use App\Proveedor;

class ProveedoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proveedor::create([
        	'nombre' => 'Acquajet',
        	'correo_contacto' => 'murcia@acquajet.com',
        	'telefono_contacto' => '670833320'
        ]);
    }
}

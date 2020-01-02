<?php

use Illuminate\Database\Seeder;
use App\Categoria;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create(['nombre' => 'Agroquímicos']);
        Categoria::create(['nombre' => 'Alquileres y portes']);
        Categoria::create(['nombre' => 'Análisis']);
        Categoria::create(['nombre' => 'Arena']);
        Categoria::create(['nombre' => 'Epis y uniform']);
        Categoria::create(['nombre' => 'Ferretería']);
        Categoria::create(['nombre' => 'Formación']);
        Categoria::create(['nombre' => 'Gasoil']);
        Categoria::create(['nombre' => 'Gasolina']);
        Categoria::create(['nombre' => 'Gestión residuos']);
        Categoria::create(['nombre' => 'Herramientas']);
        Categoria::create(['nombre' => 'Lagos']);
        Categoria::create(['nombre' => 'Limpieza fosa']);
        Categoria::create(['nombre' => 'Lubricantes']);
        Categoria::create(['nombre' => 'Material oficina']);
        Categoria::create(['nombre' => 'Otros']);
        Categoria::create(['nombre' => 'Reparaciones externas']);
        Categoria::create(['nombre' => 'Repuestos maquinaria']);
        Categoria::create(['nombre' => 'Riego']);
        Categoria::create(['nombre' => 'Semilla']);
    }
}

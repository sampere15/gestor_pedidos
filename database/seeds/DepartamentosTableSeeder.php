<?php

use Illuminate\Database\Seeder;
use App\Departamento;
use App\Campo;

class DepartamentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departamento::create(['nombre' => 'Mantenimiento']);

        Departamento::create(['nombre' => 'Proshop']);

        Departamento::create(['nombre' => 'Operaciones']);
    }
}

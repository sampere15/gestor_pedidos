<?php

use Illuminate\Database\Seeder;
use App\Campo;
use App\Departamento;

class CampoDepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//	Recuperamos todos los departamentos que tenemos dados de alta
        $departamentos = Departamento::all();

        //	Ahora vamos a indicar que cada uno de los campos tiene todos los departamentos que hemos dado de alta

        $campo1 = Campo::find(1);
        $campo1->departamentos()->attach($departamentos);

        $campo2 = Campo::find(2);
        $campo2->departamentos()->attach($departamentos);

        $campo3 = Campo::find(3);
        $campo3->departamentos()->attach(Departamento::find(2));

        $campo4 = Campo::find(4);
        $campo4->departamentos()->attach(Departamento::find(3));
    }
}

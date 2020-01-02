<?php

use Illuminate\Database\Seeder;
use App\Sociedad;
use App\Campo;

class SociedadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $soc1 = Sociedad::create([
        	'nombre' => 'GNK Desarrollos',
        	'cif' => 'B99408817'
        ]);

        // $soc2 = Sociedad::create([
        // 	'nombre' => 'SOCIEDAD DE GESTIÓN DE ACTIVOS PROCEDENTE DE LA RESTRUCTURACION BANCARIA S.A.',
        // 	'cif' => 'A86602158'
        // ]);

        // $campoMM = Campo::where('abreviatura', 'MM')->first();
        // $campoSA = Campo::where('abreviatura', 'SA')->first();
        // $campoEV = Campo::where('abreviatura', 'EV')->first();
        // $campoAS = Campo::where('abreviatura', 'AS')->first();
        // $campoLT = Campo::where('abreviatura', 'LT')->first();
        // $campoHR = Campo::where('abreviatura', 'HR')->first();

        // $camposGNK = [$campoMM, $campoSA, $campoEV, $campoAS, $campoLT, $campoHR];
        // $camposSAREB = [$campoMM, $campoSA];
        // $camposGNK = Campo::find([1, 2, 3, 4, 5, 6]);
        // $camposSAREB = Campo::find([5, 6]);

        // dd($camposSAREB);

        // $soc1->campos()->attach($camposGNK);
        // $soc2->campos()->attach($camposSAREB);
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Campo;
use App\User;
use App\Sociedad;

class CamposTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $socGNK = Sociedad::find(1);
        // $socSAREB = Sociedad::find(2);

        // dd($socGNK, $socSAREB);

        $campoAS = Campo::create([
        	'nombre' => 'Alhama',
        	'abreviatura' => 'AS',
            'sociedad_favorita_id' => $socGNK->id,
        ]);

        $campoEV = Campo::create([
        	'nombre' => 'El Valle',
        	'abreviatura' => 'EV',
            'sociedad_favorita_id' => $socGNK->id,
        ]);        

        $campoHR = Campo::create([
        	'nombre' => 'Hacienda Riquelme',
        	'abreviatura' => 'HR',
            'sociedad_favorita_id' => $socGNK->id,
        ]);

        $campoLT = Campo::create([
        	'nombre' => 'La Torre',
        	'abreviatura' => 'LT',
            'sociedad_favorita_id' => $socGNK->id,
        ]);

        // $campoMM = Campo::create([
        // 	'nombre' => 'Mar Menor',
        // 	'abreviatura' => 'MM',
        //     'sociedad_favorita_id' => $socSAREB->id,
        // ]);

        // $campoSA = Campo::create([
        // 	'nombre' => 'Saurines',
        // 	'abreviatura' => 'SA',
        //     'sociedad_favorita_id' => $socSAREB->id,
        // ]);

        $camposGNK = Campo::find([1, 2, 3, 4, 5, 6]);
        // $camposSAREB = Campo::find([5, 6]);

        $socGNK->campos()->attach($camposGNK);
        // $socSAREB->campos()->attach($camposSAREB);
    }
}

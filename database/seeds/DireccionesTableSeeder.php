<?php

use Illuminate\Database\Seeder;
use App\Direccion;
use App\Campo;

class DireccionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	//	Primero recuperamos el campo al que le vamos a asignar esta dirección
    	// $campoMM = Campo::where('abreviatura', 'MM')->firstOrFail();
        $campoEV = Campo::where('abreviatura', 'EV')->firstOrFail();
        $campoLT = Campo::where('abreviatura', 'LT')->firstOrFail();
        $campoHR = Campo::where('abreviatura', 'HR')->firstOrFail();
        $campoAS = Campo::where('abreviatura', 'AS')->firstOrFail();
        // $campoSA = Campo::where('abreviatura', 'SA')->firstOrFail();

        Direccion::create([
            'nombre' => 'Proshop El Valle',
            'calle' => 'Proshop El Valle',
            'ciudad' => 'Torre Pacheco',
            'codigo_postal' => '30700',
            'provincia' => 'Murcia',
            'pais' => 'España',
            'persona_contacto' => 'Belén Mosquera',
            'numero_contacto' => '666555888',
            'campo_id' => $campoEV->id,
        ]);

        Direccion::create([
            'nombre' => 'Proshop La Torre',
            'calle' => 'Proshop La Torre',
            'ciudad' => 'Torre Pacheco',
            'codigo_postal' => '30700',
            'provincia' => 'Murcia',
            'pais' => 'España',
            'persona_contacto' => 'Belén Mosquera',
            'numero_contacto' => '666555888',
            'campo_id' => $campoLT->id,
        ]);

        Direccion::create([
            'nombre' => 'Proshop Hacienda Riquelme',
            'calle' => 'Proshop Hacienda Riquelme',
            'ciudad' => 'Torre Pacheco',
            'codigo_postal' => '30700',
            'provincia' => 'Murcia',
            'pais' => 'España',
            'persona_contacto' => 'Belén Mosquera',
            'numero_contacto' => '666555888',
            'campo_id' => $campoHR->id,
        ]);

        // Direccion::create([
        //     'nombre' => 'Proshop Saurines',
        //     'calle' => 'Proshop Saurines',
        //     'ciudad' => 'Torre Pacheco',
        //     'codigo_postal' => '30700',
        //     'provincia' => 'Murcia',
        //     'pais' => 'España',
        //     'persona_contacto' => 'Belén Mosquera',
        //     'numero_contacto' => '666555888',
        //     'campo_id' => $campoSA->id,
        // ]);

        Direccion::create([
            'nombre' => 'Proshop Alhama',
            'calle' => 'Proshop Alhama',
            'ciudad' => 'Torre Pacheco',
            'codigo_postal' => '30700',
            'provincia' => 'Murcia',
            'pais' => 'España',
            'persona_contacto' => 'Belén Mosquera',
            'numero_contacto' => '666555888',
            'campo_id' => $campoAS->id,
        ]);

        // Direccion::create([
        //     'nombre' => 'Oficina GNK Desarrollos',
        // 	'calle' => 'c/ Ceiba s/n Edificio Town Center, Planta 1 Iz, Menor Golf Resort',
        // 	'ciudad' => 'Torre Pacheco',
        // 	'codigo_postal' => '30700',
        // 	'provincia' => 'Murcia',
        // 	'pais' => 'España',
        // 	'persona_contacto' => 'Belén Mosquera',
        // 	'numero_contacto' => '666555888',
        // 	'campo_id' => $campoMM->id,
        // ]);

        // Direccion::create([
        //     'nombre' => 'Prosohp Mar Menor',
        //     'calle' => 'Prosohp Mar Menor',
        //     'ciudad' => 'Torre Pacheco',
        //     'codigo_postal' => '30700',
        //     'provincia' => 'Murcia',
        //     'pais' => 'España',
        //     'persona_contacto' => 'Belén Mosquera',
        //     'numero_contacto' => '666555888',
        //     'campo_id' => $campoMM->id,
        // ]);

        // Direccion::create([
        //     'nombre' => 'Nave Mantenimiento',
        //     'calle' => 'Nave Mantenimiento',
        //     'ciudad' => 'Torre Pacheco',
        //     'codigo_postal' => '30700',
        //     'provincia' => 'Murcia',
        //     'pais' => 'España',
        //     'persona_contacto' => 'Belén Mosquera',
        //     'numero_contacto' => '666555888',
        //     'campo_id' => $campoMM->id,
        // ]);
    }
}

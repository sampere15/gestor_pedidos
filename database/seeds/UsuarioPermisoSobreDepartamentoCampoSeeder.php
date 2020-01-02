<?php

use Illuminate\Database\Seeder;
use App\Usuario;
use App\Departamento;
use App\Campo;
use App\CampoDepartamento;
use App\UsuarioPermisoSobreDepartamentoCampo;

class UsuarioPermisoSobreDepartamentoCampoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //	Campo El Valle y departamento Mantenimiento
        $campoEV = Campo::where('nombre', 'El Valle')->first();
        $departamentoMantenimiento = Departamento::where('nombre', 'Mantenimiento')->first();
        $campoDepartamento_EV_Mantenimiento = CampoDepartamento::where('campo_id', $campoEV->id)->where('departamento_id', $departamentoMantenimiento->id)->first();

    	
        UsuarioPermisoSobreDepartamentoCampo::create([
        	'usuario_id' => 1,
        	'campo_departamento_id' => $campoDepartamento_EV_Mantenimiento->id,
        ]);
    }
}

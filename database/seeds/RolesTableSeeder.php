<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Rol que tendrá acceso a todas las funcionalidades
        Role::create([
        	'name' => 'Administrador',
        	'slug' => 'administrador',
        	'special' => 'all-access'
        ]);
    }
}

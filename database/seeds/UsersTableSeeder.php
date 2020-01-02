<?php

use Illuminate\Database\Seeder;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use App\Campo;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Recuperamos el campo al que pertenece el usuario
        $campo = Campo::where('abreviatura', 'EV')->firstOrfail();

        $user1 = User::create([
            'nombre' => 'Sistemas',
            'apellidos' => 'GNK',
            'nif' => '12345678A',
            'email' => 'sistemas@gnkgolf.com',
            'password' => bcrypt('gnk.123'),
        ]);

        //  Recuperamos el rol administrador para asignárselo al usuario
        $rolAdministrador = Role::where('slug', 'administrador')->first();

        $user1->assignRole($rolAdministrador->id);
    }
}

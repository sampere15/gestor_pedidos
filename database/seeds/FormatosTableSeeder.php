<?php

use Illuminate\Database\Seeder;
use App\Formato;

class FormatosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Formato::create(['nombre' => 'hora']);
        Formato::create(['nombre' => 'kilo']);
        Formato::create(['nombre' => 'litro']);
        Formato::create(['nombre' => 'm2']);
        Formato::create(['nombre' => 'm3']);
        Formato::create(['nombre' => 'metro']);
        Formato::create(['nombre' => 'tonelada']);
        Formato::create(['nombre' => 'unidad']);
        Formato::create(['nombre' => 'dia']);
    }
}

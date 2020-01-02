<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Campo;

class Sociedad extends Model
{
    protected $fillable = ['nombre', 'cif'];

    protected $table = 'sociedades';

    public $timestamps = false;	//	Para evitar que intente insertar las fechas created_at y updated_at, que para esta tabla no las necesitamos

    public function campos()
    {
    	return $this->belongsToMany(Campo::class);
    }

    //	Nos indica si una sociedad gestiona un campo
    public function GestionaCampo($campoNombre)
    {
    	$resultado = false;

    	foreach ($this->campos as $campo)
    	{
    		if($campo->nombre == $campoNombre)
    		{
    			$resultado = true;
    			break;
    		}
    	}
    	return $resultado;
    }
}

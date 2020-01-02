<?php

namespace App;
use App\Campo;
use App\Departamento;

use Illuminate\Database\Eloquent\Model;

class CampoDepartamento extends Model
{
    protected $table = 'campo_departamento';

    //	Creamos la relación con la tabla campo
    public function campo()
    {
    	return $this->belongsTo(Campo::class);
    }

    //	Creamos la relación con la tabla departamento
    public function departamento()
    {
    	return $this->belongsTo(Departamento::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoLinea extends Model
{
    public $timestamps = false;	//	Para evitar que intente insertar las fechas created_at y updated_at, que para esta tabla no las necesitamos

    protected $table = "estados_lineas";
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formato extends Model
{
    protected $fillable = ['nombre'];

    protected $table = 'formatos';

    public $timestamps = false;	//	Para evitar que intente insertar las fechas created_at y updated_at, que para esta tabla no las necesitamos
}

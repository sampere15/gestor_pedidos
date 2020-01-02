<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
	//	Nombre de la tabla en la BBDD
	protected $table = 'departamentos';

	//	No necesitamos saber cuando se han creado o actualizado los departamentos	
	public $timestamps = false;

	protected $fillable = ['nombre'];

    public function campos()
    {
        return $this->belongsToMany(Campo::class);
    }

    public function pedidos()
    {
    	// hasMany(RelatedModel, foreignKeyOnRelatedModel = pedido_id, localKey = id)
    	return $this->hasMany(Pedido::class);
    }

    public function TieneCampo($campo_id)
    {
        $resultado = $this->campos->contains($campo_id);

        return $resultado;
    }
}

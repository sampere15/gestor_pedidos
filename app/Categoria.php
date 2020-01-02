<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre'];

    protected $table = 'categorias';

    public $timestamps = false; //  Para evitar que intente insertar las fechas created_at y updated_at, que para esta tabla no las necesitamos

    /**
     * Campo has many Descripciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function descripciones()
    {
    	// hasMany(RelatedModel, foreignKeyOnRelatedModel = descripcion_id, localKey = id)
    	return $this->hasMany(Descripcion::class);
    }

    public function pedidos()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = pedido_id, localKey = id)
        return $this->hasMany(Pedido::class);
    }
}

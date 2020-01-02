<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $fillable = ['nombre', 'calle', 'ciudad', 'codigo_postal', 'provincia', 'pais', 'persona_contacto', 'numero_contacto', 'campo_id'];

    protected $table = 'direcciones';

    /**
     * Direccion belongs to Campo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campo()
    {
        // belongsTo(RelatedModel, foreignKey = campo_id, keyOnRelatedModel = id)
        return $this->belongsTo(Campo::class);
    }

    //  Los pedidos que se han echo con la direccion
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}

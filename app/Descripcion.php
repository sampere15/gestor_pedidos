<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descripcion extends Model
{
    protected $fillable = ['nombre'];

    protected $table = 'descripciones';

    /**
     * Direccion belongs to Campo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        // belongsTo(RelatedModel, foreignKey = categoria_id, keyOnRelatedModel = id)
        return $this->belongsTo(Categoria::class);
    }
}

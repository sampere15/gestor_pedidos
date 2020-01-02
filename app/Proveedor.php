<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\Pedido;

class Proveedor extends Model
{
    protected $fillable = ['nombre', 'cif', 'direccion', 'provincia', 'pais', 'persona_contacto', 'telefono_contacto', 'correo_contacto'];

    protected $table = 'proveedores';

    /**
     * Proveedor has many Pedidos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pedidos()
    {
    	// hasMany(RelatedModel, foreignKeyOnRelatedModel = pedido_id, localKey = id)
    	return $this->hasMany(Pedido::class);
    }
}

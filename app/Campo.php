<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sociedad;

class Campo extends Model
{
    protected $fillable = ['nombre', 'abreviatura', 'sociedad_favorita_id'];

    protected $table = 'campos';

    public $timestamps = false; //  Para evitar que intente insertar las fechas created_at y updated_at, que para esta tabla no las necesitamos

    /**
     * Campo has many Direcciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function direcciones()
    {
    	return $this->hasMany(Direccion::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function sociedadFavorita()
    {
        return $this->belongsTo(Sociedad::class, 'sociedad_favorita_id');
    }

    public function sociedades()
    {
        return $this->belongsToMany(Sociedad::class);
    }

    public function departamentos()
    {
        return $this->belongsToMany(Departamento::class);
    }

    public function TieneDepartamento($departamento_id)
    {
        $resultado = $this->departamentos->contains($departamento_id);

        return $resultado;
    }

    public function pedidos()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = pedido_id, localKey = id)
        return $this->hasMany(Pedido::class);
    }
}

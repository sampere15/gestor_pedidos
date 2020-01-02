<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    protected $table = 'estados_pedidos';

    protected $fillable = ['nombre', 'descripcion', 'nombre_mostrar'];

    public $timestamps = false;	//	Para evitar que intente insertar las fechas created_at y updated_at, que para esta tabla no las necesitamos

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}

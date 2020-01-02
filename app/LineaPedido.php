<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pedido;
use App\EstadoPedido;
use App\Categoria;
use App\Formato;

class LineaPedido extends Model
{
    protected $fillable = ['pedido_id', 'numero_linea', 'desripcion', 'unidades', 'precio', 'categoria_id', 'formato_id'];

    protected $table = 'lineas_pedidos';

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function estadoLinea()
    {
        return $this->belongsTo(EstadoLinea::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function formato()
    {
        return $this->belongsTo(Formato::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pedido;
use App\User;
use App\LineaPedido;

class HistoricoEstadoPedido extends Model
{
	public $timestamps = false;	//	Para evitar que intente insertar las fechas created_at y updated_at, que para esta tabla no las necesitamos

    protected $fillable = ['pedido_id', 'estado', 'usuario_id', 'fecha'];

    protected $table = 'historico_estados_pedidos';

    //	Pedio al que hace referencia el histórico
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    //	Usuario que ha gestionado el estado actual del pedido
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}

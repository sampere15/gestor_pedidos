<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EstadoPedido;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = ['fecha_pedido', 'usuario_realiza_pedido_id', 'proveedor_id', 'campo_id', 'sociedad_id', 'direccion_id', 'estado_pedido_id'];

    //	Usuario que realiza el pedido
    public function usuarioRealizaPedido()
    {
        return $this->belongsTo(User::class, 'usuario_realiza_pedido_id');
    }

    //	Proveedor al que se le hace el pedido
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    //	Campo para el que se hace el pedido
	public function campo()
    {
        return $this->belongsTo(Campo::class);
    }

    //  Departamento para el que se realiza el pedido
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    //	Sociedad para la que se hace el pedido
    public function sociedad()
    {
        return $this->belongsTo(Sociedad::class);
    }

    //	Dirección de entrega del pedido
    public function direccion()
    {
        return $this->belongsTo(Direccion::class);
    }

    //	Estado en el que se encuentra el pedido [pendiente de aprobar, aprobado, recibido, recibido parcial...]
    public function estadoPedido()
    {
        return $this->belongsTo(EstadoPedido::class);
    }

    //  Las lineas de pedido que tiene el pedido
    public function lineasPedido()
    {
        return $this->hasMany(LineaPedido::class);
    }

    //  Información histórica con los diferentes estados del pedido y quien los ha hecho y cuando
    public function historicoEstados()
    {
        return $this->hasMany(HistoricoEstadoPedido::class);
    }

    //  Nos indica si un pedido ya ha pasado por cierto estado consultando su histórico
    public function HistoricoTieneEstado($estado)
    {
        $resultado = null;

        foreach ($this->historicoEstados as $historico) 
        {
            if($historico->estado == $estado)
            {
                $resultado = $historico;
                break;
            }
        }

        return $resultado;
    }

    //  Nos indica el usuario que ha validado el pedido en el caso de que ya haya sido validado. Si no, nos devolverá NULL
    public function usuarioHaValidado()
    {
        $resultado = $this->HistoricoTieneEstado('validado');

        if($resultado != null)
            return $resultado->usuario;
        else
            return null;
    }
}

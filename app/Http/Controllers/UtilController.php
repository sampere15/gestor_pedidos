<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use App\Pedido;
use App\EstadoPedido;
use App\Helpers\MiHelper;

//  PRUEBAS BORRAR!!
use App\CampoDepartamento;
use App\UsuarioPermisoSobreDepartamentoCampo;
//  PRUEBAS BORRAR!!

class UtilController extends Controller
{
    public function EnviarEmailResetPassword($email)
    {
        $credentials = ['email' => $email];
        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));
            case Password::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

    //  Comprueba si hay pedidos cursados que todavía no se hayan comunicado al proveedor
    public function comprobarpedidospendientescomunicar()
    {
        $estadosPosibles = EstadoPedido::whereIn('nombre', ['cursado', 'pendiente_recibir', 'recibido_parcialmente', 'finalizado'])->pluck('id');
        $pedidos = Pedido::where('pedido_comunicado', false)->where('cancelado', false)->whereIn('estado_pedido_id', $estadosPosibles)->get();
        
        //  Llamamos a la función que se encarga de enviar el correo a los diferentes usuarios con la información necesaria
        // MiHelper::EnviarEmailPedidosSinComunicarAlProveedor(count($pedidos));
        MiHelper::EnviarEmailPedidosSinComunicarAlProveedor($pedidos);
    }

    //  Comprueba si hay pedidos validados pendientes de cursar
    public function comprobarpedidospendientescursar()
    {
        $estado = EstadoPedido::where('nombre', 'validado')->firstOrFail();
        $pedidos = Pedido::where('estado_pedido_id', $estado->id)->get();

        //  LLamamos a la función que se encarga de enviar el correo a los diferentes usuarios con el permiso de validar para los pedidos dados
        MiHelper::EnviarEmailPedidosSinCursar($pedidos);
    }

    //  Comprueba si hay pedidos solicitados pendientes de validar
    public function comprobarpedidospendientesvalidar()
    {
        $estado = EstadoPedido::where('nombre', 'solicitado')->firstOrFail();
        $pedidos = Pedido::where('estado_pedido_id', $estado->id)->get();

        MiHelper::EnviarEmailPedidosSinValidar($pedidos);
    }

    public function test()
    {
        // $user = Auth::user();
        // $user->camposDepartamentosConPermiso();
        // dd($user->tienePermisoSobreDepartamentoCampo->first()->campoDepartamento->campo->nombre);
    }
}

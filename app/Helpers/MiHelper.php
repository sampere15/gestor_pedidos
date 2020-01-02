<?php

namespace App\Helpers;

use App\Pedido;
use App\User;
use Illuminate\Support\Facades\Mail;    //  Para poder enviar email's
use Illuminate\Support\Facades\DB;
use App\Mail\AvisoPedidoCancelado;
use App\Mail\AvisoPedidos;
use Caffeinated\Shinobi\Models\Permission;

class MiHelper
{
	//  En el caso de que se haya cancelado un pedido enviará un correo a la persona que lo validó para que esté avisada
    public static function EnviarEmailPedidoCancelado(Pedido $pedido, User $usuario)
    {
        $datos = [
            'asunto' => 'Pedido Cancelado',
            'titulo' => 'Pedidos GNK',
            'cuerpo' => 'El pedido con ID ' . $pedido->id . ' ha sido cancelado por el usuario ' . $usuario->nombre . ' ' . $usuario->apellidos . '. A continuación podrá ver el contenido del pedido:',
            'url_pedido' => route('pedidos.verdetalles', $pedido),
            'pedido' => $pedido,
        ];

        //  Recuperamos el usuario que ha validado el pedido y le mandamos el email con el aviso
        $usuario = $pedido->usuarioHaValidado();
        Mail::to($usuario->email)->send(new AvisoPedidoCancelado($datos));
    }

    //	Envía correo indicando si hay pedidos pendientes de comunicar al proveedor
	public static function EnviarEmailPedidosSinComunicarAlProveedor($pedidos)
    {
		//	Formateamos los id's en el string que necesita la consulta
		$pedidos_ids = MiHelper::FormateaArrayIDsParaFiltroIN($pedidos->pluck('id')->toArray());
		
		//	Recuperar los usuarios que tienen permiso para comunicar al proveedor para el departamento-campo de los pedidos
		$usuarios = MiHelper::ObtenerUsuariosSegunPermisoYPedidos('pedidos.comunicaraproveedor', $pedidos_ids);

		$datos = [
			'asunto' => 'Pedidos pendientes de comunicar al proveedor',
		];

		if(count($pedidos) > 0)
		{
			$datos['titulo'] = 'Hay ' . count($pedidos) . ' pedidos pendientes de comunicar al proveedor';
			$datos['url'] = route('pedidos.listarpendientescomunicar');
			$datos['textourl'] = "Puede consultar los pedidos que están pendientes de comunicar al proveedor accediendo al siguiente enlace: ";
		}
		else
		{
			$datos['titulo'] = 'No hay pedidos pendientes de comunicar al proveedor';	
		}

		//	Añadimos el correo de sistemas ya que este usuario tiene un rol especial y no se rige por los permisos
		$emails = $usuarios->pluck('email')->toArray();
		array_push($emails, 'sistemas@gnkgolf.com');

		//	Finalmente mandamos el correo
		Mail::to($emails)->send(new AvisoPedidos($datos));
	}

	//	Envía correo indicando si hay pedidos pendientes de cursar
	public static function EnviarEmailPedidosSinCursar($pedidos)
	{
		//	Formateamos los id's en el string que necesita la consulta
		$pedidos_ids = MiHelper::FormateaArrayIDsParaFiltroIN($pedidos->pluck('id')->toArray());
		
		//	Recuperar los usuarios que tienen permiso para comunicar al proveedor para el departamento-campo de los pedidos
		$usuarios = MiHelper::ObtenerUsuariosSegunPermisoYPedidos('pedidos.cursar', $pedidos_ids);

		$datos = [
			'asunto' => 'Pedidos pendientes de cursar',
		];

		if(count($pedidos) > 0)
		{
			$datos['titulo'] = 'Hay ' . count($pedidos) . ' pedidos pendientes de cursar';
			$datos['url'] = route('pedidos.listarvalidados');
			$datos['textourl'] = "Puede consultar los pedidos que están pendientes de cursar accediendo al siguiente enlace: ";
		}
		else
		{
			$datos['titulo'] = 'No hay pedidos pendientes de cursar';	
		}

		//	Añadimos el correo de sistemas ya que este usuario tiene un rol especial y no se rige por los permisos
		$emails = $usuarios->pluck('email')->toArray();
		array_push($emails, 'sistemas@gnkgolf.com');

		//	Finalmente mandamos el correo
		Mail::to($emails)->send(new AvisoPedidos($datos));
	}

	//	Envía correo indicando si hay pedidos pendientes de validar
	public static function EnviarEmailPedidosSinValidar($pedidos)
	{
		//	Formateamos los id's en el string que necesita la consulta
		$pedidos_ids = MiHelper::FormateaArrayIDsParaFiltroIN($pedidos->pluck('id')->toArray());
		
		//	Recuperar los usuarios que tienen permiso para comunicar al proveedor para el departamento-campo de los pedidos
		$usuarios = MiHelper::ObtenerUsuariosSegunPermisoYPedidos('pedidos.validar', $pedidos_ids);

		$datos = [
			'asunto' => 'Pedidos pendientes de validar',
		];

		if(count($pedidos) > 0)
		{
			$datos['titulo'] = 'Hay ' . count($pedidos) . ' pedidos pendientes de validar';
			$datos['url'] = route('pedidos.listarvalidados');
			$datos['textourl'] = "Puede consultar los pedidos que están pendientes de validar accediendo al siguiente enlace: ";
		}
		else
		{
			$datos['titulo'] = 'No hay pedidos pendientes de validar';
		}

		//	Añadimos el correo de sistemas ya que este usuario tiene un rol especial y no se rige por los permisos
		$emails = $usuarios->pluck('email')->toArray();
		array_push($emails, 'sistemas@gnkgolf.com');

		//	Finalmente mandamos el correo
		Mail::to($emails)->send(new AvisoPedidos($datos));
	}

	//	Recibe un array de id's que queremos filtrar en una query en un "IN", nos devuelve el string necesario
	public static function FormateaArrayIDsParaFiltroIN($array_ids)
	{
		//	Simplemente vamos a formatear para obterner un string tipo -> (1, 2, 3)
		$string_array_ids = '(';
		for($i = 0; $i < count($array_ids); $i++)
		{
			$string_array_ids .= $array_ids[$i];
			if($i < count($array_ids) -1)
				$string_array_ids .= ', ';
		}
		$string_array_ids .= ')';

		return $string_array_ids;
	}

	//	Obtiene los usuarios que tiene cierto permiso según para qué pedidos
	public static function ObtenerUsuariosSegunPermisoYPedidos($permiso_nombre, $pedidos_id)
	{
		// dd($pedidos_id);
		$query = DB::select(
			'SELECT DISTINCT(users.id)
			FROM users, permission_user, permissions, usuario_puede_departamento_campo, campo_departamento
			WHERE users.id = permission_user.user_id
			AND permissions.id = permission_user.permission_id
			And permissions.slug LIKE ?
			AND users.id = usuario_puede_departamento_campo.usuario_id
			AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id
			AND (campo_departamento.departamento_id, campo_departamento.campo_id) IN
			(
				SELECT pedidos.departamento_id, pedidos.campo_id 
				FROM pedidos
				WHERE pedidos.id IN ' . $pedidos_id . ' 
			)'
			, [$permiso_nombre]);

		$array_usuarios = array();
		//	Formateamos el resutlado para poder aplicarlo en la función de Eloquent "find($array)"
		foreach($query as $registro)
		{
			array_push($array_usuarios, $registro->id);
		}

		$usuarios = User::find($array_usuarios);

		return $usuarios;
	}
}
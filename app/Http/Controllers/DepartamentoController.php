<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departamento;
use App\Campo;
use App\Pedido;
use App\User;
use Illuminate\Support\Facades\Session; //  Para poder mandar los mensajes de session para mostrar notificaciones
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
	//	Muestra el formulario que permite crear departamentos
    public function crear()
    {
    	return view('departamentos.crear', compact('campo'));
    }

    //	Guarda el departamento con los datos que nos han pasado desde el formulario
    public function guardar(Request $request)
    {
    	//	Validamos los datos que nos llegan
    	$datos = $request->validate([
    		'nombre' => 'required',
    	], [
    		'nombre.required' => 'Debe de indicar un nombre para el nuevo departamento',
    	]);

    	try 
    	{
    		//	Guardamos el departamento
    		Departamento::create([
    			'nombre' => $datos['nombre'],
    		]);

    		Session::flash('exito', 'Departamento creado con éxito');
    	} catch (\Exception $e) {
			Session::flash('error', 'Ha ocurrido un error, consulte con el administrador');
    	}

    	return redirect()->route('departamentos.crear');
    }

    //  Muestra en un listado todos los departamentos que tenemos creados
    public function listar()
    {
        $departamentos = Departamento::all();

        return view('departamentos.listar', compact('departamentos'));
    }

    //  Muestra los detalles de un departamento y también mostrará las opciones para editarlo o eliminarlo
    public function verdetalles(Departamento $departamento)
    {
        //  Aquí vamos a recuperar la información sobre qué usuarios tienen permiso para realizar pedidos, validar, cursar, comunicar al proveedor y indicar material como recibido
        $usuarios_crear_pedidos = $this->usuariosSegunDepartamentoYPermiso($departamento->id, 'pedidos.crear');
        $usuarios_validar_pedidos = $this->usuariosSegunDepartamentoYPermiso($departamento->id, 'pedidos.validar');
        $usuarios_cursar_pedidos = $this->usuariosSegunDepartamentoYPermiso($departamento->id, 'pedidos.cursar');
        $usuarios_comunicaraproveedor_pedidos = $this->usuariosSegunDepartamentoYPermiso($departamento->id, 'pedidos.comunicaraproveedor');
        $usuarios_recepcionar_pedidos = $this->usuariosSegunDepartamentoYPermiso($departamento->id, 'pedidos.recepcionar');

        return view('departamentos.detallesdepartamento', compact('departamento', 'usuarios_crear_pedidos', 'usuarios_validar_pedidos', 'usuarios_cursar_pedidos', 'usuarios_comunicaraproveedor_pedidos', 'usuarios_recepcionar_pedidos'));
    }

    public function usuariosSegunDepartamentoYPermiso($departamento_id, $permiso_slug)
    {
        //  Ususarios que pueden crear pedidos
        $array_usuarios = array();
        $query = DB::select(
            "SELECT DISTINCT(users.id)
            FROM users, permission_user, permissions, usuario_puede_departamento_campo, campo_departamento, departamentos
            WHERE permission_user.user_id = users.id
            AND permission_user.permission_id = permissions.id
            AND usuario_puede_departamento_campo.usuario_id = users.id
            AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id
            AND departamentos.id = campo_departamento.departamento_id
            AND permissions.slug LIKE " . "'" . $permiso_slug . "'" . 
            " AND departamentos.id = " . $departamento_id
        );

        foreach ($query as $registro) {
            array_push($array_usuarios, $registro->id);
        }

        $usuarios = User::find($array_usuarios);

        return $usuarios;
    }

    //  Muestra el formulario para poder editar el departamento
    public function editar(Departamento $departamento)
    {
        return view('departamentos.editar', compact('departamento'));
    }

    //  Actualiza los datos del departamento con los datos que nos llegan desde el formulario
    public function actualizar(Departamento $departamento)
    {
        $datos = request()->validate([
            'nombre' => 'required',
        ], [
            'nombre.required' => 'Debe de indicar un nombre',
        ]);

        try {
            $departamento->nombre = $datos['nombre'];
            $departamento->save();

            Session::flash('exito', 'Departamento actualizado correctamente');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'Ha ocurrido un erro, contacte con el administrador');
        }

        return redirect()->route('departamentos.listar');
    }

    //  Elimina un departamento si no ha sido utilizado. Si no, mostrará un listado de los centros que lo usa
    public function borrar(Departamento $departamento)
    {
        $cantPedidos = Pedido::where('departamento_id', $departamento->id)->count();
        
        //  Si ya se ha realizado algún pedido para el departamento que queremos borrar no lo vamos a permitir y mostraremos 
        if($cantPedidos > 0)
        {
            //  Comprobar si hay campos que lo siguen teneniendo configurado, en este caso mostrar lista para que lo quiten manualmente de cada centro antes de eliminarlo
            $campos = Campo::whereHas('departamentos', function($q) use ($departamento){
                $q->where('departamento_id', $departamento->id);
            })->pluck('nombre');

            //  Si es que si, mostramos los campos que tienen dicho departamento configurado
            if($campos->count() >= 1)
            {
                $nombreCampos = "";

                //  Como queremos que nos muestre una lista, vamos a preprar una lista de HTML
                $nombreCampos = '<ul>';
                
                foreach ($campos as $campo) 
                {
                    $nombreCampos .= '<li>' . $campo . '</li>';
                }
                
                $nombreCampos .= '</ul>';

                Session::flash('error', 'No se puede eliminar este departamento porque los siguientes campos lo tienen configurado: ' . $nombreCampos);
                return redirect()->route('departamentos.verdetalles', $departamento);
            }

            //  Si ya no está asignado a ningún centro pero se han hecho pedidos con él, lo que vamos a hacer es desactivarlo para que ningún centro lo pueda volver a usar
            else
            {
                try {
                    $departamento->activo = false;
                    $departamento->save();
                } catch (\Exception $e) {
                    dd($e);
                    Session::flash('error', 'Ha ocurrido un error, consulte con el administrador');
                }
            }
        }
        //  Si no se ha usado, procederemos a eliminarlo si es posible
        else
        {
            try {
                //  Antes de borrar comprobamos si está asignado a algún departamento
                $campos = Campo::whereHas('departamentos', function($q) use ($departamento){
                    $q->where('departamento_id', $departamento->id);
                })->pluck('nombre');

                if($campos->count() >= 1)
                {
                    $nombreCampos = "";

                    //  Como queremos que nos muestre una lista, vamos a preprar una lista de HTML
                    $nombreCampos = '<ul>';
                    
                    foreach ($campos as $campo) 
                    {
                        $nombreCampos .= '<li>' . $campo . '</li>';
                    }
                    
                    $nombreCampos .= '</ul>';

                    Session::flash('error', 'No se puede eliminar este departamento porque los siguientes campos lo tienen configurado: ' . $nombreCampos);
                    return redirect()->route('departamentos.verdetalles', $departamento);
                }
                else
                {
                    //  Esto no hace falta porque no tiene campos, por eso ha entrado aqui
                    // //  Con esto deberíamos dejar limpia la relación que tienen los campos con este departamento
                    // $departamento->campos()->sync();
                    //  Ahora ya podemos proceder a eliminar el departamento
                    $departamento->delete();
                    Session::flash('exito', 'El departamento se ha borrado correctamente');
                    return redirect()->route('departamentos.listar');
                }
            } catch (\Exception $e) {
                dd($e);
                Session::flash('error', 'Ha ocurrido un error, consulte con el administrador');
            }
        }
    }

    public function editarcampos(Departamento $departamento)
    {
        $campos = Campo::where('activo', true)->get();

        return view('departamentos.editarcampos', compact('departamento', 'campos'));
    }

    public function actualizarcampos(Departamento $departamento)
    {
        $datos = request()->input('campos');

        // $aux = $departamento->campos()->pluck('campo_id')->toArray();

        // dd($datos, $aux);

        // $resultado = array_diff($datos, $departamento->campos()->pluck('campo_id')->toArray());

        // dd($resultado);

        try {
            //  Recuperamos los campos que se han marcado
            $campos = Campo::find($datos);
            //  Actualizamos los registros
            $departamento->campos()->sync($campos);

            Session::flash('exito','Se han actualizado los campos de este departamento correctamente');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'Ha ocurrido un error, consulte con el administrador');   
        }
        return redirect()->route('departamentos.verdetalles', $departamento);
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\Campo;
use App\Departamento;
use App\CampoDepartamento;
use App\UsuarioPermisoSobreDepartamentoCampo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;	//	Para poder mandar los mensajes de session para mostrar notificaciones
use Illuminate\Support\Facades\DB;
use Caffeinated\Shinobi\Models\Permission;
use Caffeinated\Shinobi\Models\Role;


class UserController extends Controller
{
	//	Lista los usuarios que hay almacenados en la BBDD
    public function listar()
    {
    	// Sólo los usuarios con super permisos podrán ver los usuarios administradores
    	// if(Auth::user()->can('usuarios.editarpermisosadministrador'))
    	// 	$usuarios = User::all();
    	// else
    	// 	$usuarios = User::where('nombre', '<>', 'Sistemas')->get();
        
        $usuario = Auth::user();

        if($usuario->isRole('administrador'))
        {
            $usuarios = User::all();
        }
        else
        {
            //  Sólo vamos a listar los usuarios que tengan permiso para operar en los mismos departamentos-campos que nosotros (al menos para uno de ellos). Luego ya sólo podremos visualizar los pedidos que exclusivamente tengamos los mismos permisos
            $query = DB::select(
                "SELECT distinct(usuario_puede_departamento_campo.usuario_id)
                FROM usuario_puede_departamento_campo, campo_departamento
                WHERE usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id
                AND (campo_departamento.departamento_id, campo_departamento.campo_id) IN
                    (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                    FROM usuario_puede_departamento_campo, campo_departamento
                    WHERE usuario_puede_departamento_campo.usuario_id = ?
                    AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)
                "
            , [$usuario->id]);

            $array_usuarios_id = array();
            foreach($query as $registro)
            {
                array_push($array_usuarios_id, $registro->usuario_id);
            }

            $usuarios = User::find($array_usuarios_id);
        }


    	return view('usuarios.listar', compact('usuarios'));
    }

    //	Muestra la página para crear el usuario
    public function crear()
    {
    	$campos = Campo::all();

        //  Si quien va a crear el usuario es un superadmin puede asignar permisos elevados, si no, no
        if(Auth::user()->can('usuarios.editarpermisosadministrador'))
            $permisos = Permission::all();
        else
            $permisos = Permission::where('slug', 'NOT LIKE', '%admin%')->get();

        $categoriasPermisos = array();

        //  Obtenemos las diferentes categorías de permisos (Usuarios, Pedidos...)
        foreach ($permisos as $permiso => $valor) 
        {
            $pos = stripos($valor->slug, ".");
            $categoria = ucfirst(substr($valor->slug, 0, $pos));
            array_push($categoriasPermisos, $categoria);
        }

        $categoriasPermisos = array_unique($categoriasPermisos);

    	return view('usuarios.crear', compact('campos', 'permisos', 'categoriasPermisos'));
    }

    //	Crea un usuario con los datos introducidos en el formulario
    public function generar(Request $request)
    {
        // dd($request->all());

        //  Validamos los datos que nos vienen desde el formulario
        $datos = $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'nif' => 'required|max:9',
            'email' => 'email',
        ],[
            'nombre.required' => 'Debe indicar el nombre del usuario',
            'apellidos.required' => 'Debe indicar los apellidos del usuario',
            'nif.required' => 'Debe indicar el NIF del usuario',
            'nif.max' => 'El NIF tiene que tener formato 12345678A, letra junto a los números, sin espacios ni guión',
            'email.email' => 'El email tiene que tener formato de correo electrónico (correo@ejemplo.com)',
        ]);

        //  Creamos el usuario con los datos que nos han pasado. La contraseña la generamos de forma aleatoria. El usuario recibirá un correo para configurar pass
        $usuario = User::create([
            'nombre' => $datos['nombre'],
            'apellidos' => $datos['apellidos'],
            'nif' => $datos['nif'],
            'email' => $datos['email'],
            'activo' => true,
            // 'password' => Hash::make(str_random(8)),
            'password' => bcrypt("gnk.123"),
        ]);

        //  Si el usuario tiene permisos para modificar/asignar permisos la variable existirá, si no, asignaremos los permisos que nosotros indiquemos que son los de por defecto
        $permisos = array();
        if($request->has('permisos'))
            $permisos = Permission::find($request->input('permisos'));
        else
            $permisos = $this->ObtenerPermisosPorDefecto();

        // dd($permisos);

        //  Asignamos los permisos al usuario
        foreach ($permisos as $permiso) 
        {
            $usuario->assignPermission($permiso->id);
        }

        $usuario->save();

    	//	Ahora enviaríamos el correo para configurar la contraseña
		// app('App\Http\Controllers\UtilController')->EnviarEmailResetPassword($usuario->email);

    	//	Mostramos mensaje indicando si se ha creado o no el usuario
    	if($usuario->wasRecentlyCreated)
    		Session::flash('exito', 'Usuario generado con éxito. Se ha enviado un correo para que configure su contraseña');
    	else
    		Session::flash('error', 'Ha ocurrido un error al guardar el usuario, pruebe más tarde o contacte con el administrador');


    	return redirect()->route('usuarios.crear');
    }

    //	Cuando creamos un usuario, le asignamos sus permisos por defecto
    private function ObtenerPermisosPorDefecto()
    {
        // $permisos = array();
        // $permiso = Permission::OrWhere('slug', 'pedidos.crear')->OrWhere('slug', 'pedidos.editar');
        $permisos = Permission::whereIn('slug', array('pedidos.crear', 'pedidos.editar', 'pedidos.verdetalles'))->get();
        // dd($permisos);
        // array_push($permisos, $permisos);

        return $permisos;
    }

    //  Entra en la ficha del cliente para modificar los permisos del usuario
    public function Editar(User $usuario)
    {
        $campos = Campo::all();

        //  Si quien va a crear el usuario es un superadmin puede asignar permisos elevados, si no, no
        if(Auth::user()->can('usuarios.editarpermisosadministrador'))
            $permisos = Permission::all();
        else
            $permisos = Permission::where('slug', 'NOT LIKE', '%admin%')->get();

        $categoriasPermisos = array();

        //  Obtenemos las diferentes categorías de permisos (Usuarios, Pedidos...)
        foreach ($permisos as $permiso => $valor) 
        {
            $pos = stripos($valor->slug, ".");
            $categoria = ucfirst(substr($valor->slug, 0, $pos));
            array_push($categoriasPermisos, $categoria);
        }

        $categoriasPermisos = array_unique($categoriasPermisos);

        return view('usuarios.editar', compact('usuario', 'campos', 'permisos', 'categoriasPermisos'));
    }

    //  Actualiza los datos del usuario
    public function Actualizar(Request $request, User $usuario)
    {
        //  Validamos los datos que nos vienen desde el formulario
        $datos = $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'nif' => 'required|max:9',
            'email' => 'email',
        ],[
            'nombre.required' => 'Debe indicar el nombre del usuario',
            'apellidos.required' => 'Debe indicar los apellidos del usuario',
            'nif.required' => 'Debe indicar el NIF del usuario',
            'nif.max' => 'El NIF tiene que tener formato 12345678A, letra junto a los números, sin espacios ni guión',
            'email.email' => 'El email tiene que tener formato de correo electrónico (correo@ejemplo.com)',
        ]);

        //  Actualizamos los datos del usuario
        $usuario->nombre = $datos['nombre'];
        $usuario->apellidos = $datos['apellidos'];
        $usuario->nif = $datos['nif'];
        $usuario->email = $datos['email'];

        //  Si tiene el usuario el permiso para actualizar los permisos
        if(Auth::user()->can('usuarios.editarpermisos'))
        {
            $usuario->syncPermissions($request->input('permisos'));
        }

        try 
        {
            $usuario->save();
            Session::flash('exito', 'Datos del usuario actualizados correctamente');
        } 
        catch (\Exception $e) {
            Session::flash('error', 'Ha ocurrido un error al actualizar los datos del usuario, pruebe más tarde o contacte con el administrador');
        }

        return redirect()->route('usuarios.verdetalles', $usuario);
    }

    //  Muestra la información del usuario
    public function verdetalles(User $usuario)
    {
        //  Restringimos para que los datos de un admin sólo los pueda ver otro admin
        if(Auth::user()->isRole('administrador') || !$usuario->isRole('administrador'))
        {
            $array_departamentos_campos = array();

            //  Recuperamos los departamentos para los que tiene permiso el usuario
            $departamentos = $usuario->departamentosConPermiso();

            //  Para departamento obtenemos en qué campos tiene permiso el usuario
            foreach ($departamentos as $departamento) 
            {
                $campos = $usuario->camposConPermisoSegunDepartamento($departamento->id);
                $array_departamentos_campos[$departamento->id] = $campos;
            }
            
            return view('usuarios.detallesusuario', compact('usuario', 'departamentos', 'array_departamentos_campos'));
        }
        else
        {
            return back();
        }
    }

    //  Borra un usuario de la BBDD
    public function borrar(User $usuario)
    {
        try {
            //  Hacemos las comprobaciones normales si no es un superuser
            if(!$usuario->isRole('administrador'))
            {
                //  Si el usuario ha realizado algún pedido no lo vamos a eliminar, lo ocultaremos para no perder información
                if($usuario->pedidos->count() >= 1)
                {
                    $usuario->activo = false;
                    //  Como doble check, le quitamos todos los permisos y roles
                    $usuario->revokeAllPermissions();
                    $usuario->revokeAllRoles();
                    $usuario->save();
                    Session::flash('exito', 'El usuario ya ha realizado pedido y por lo tanto no se puede eliminar. Se ha desactivado para que no se pueda volver a usar.');
                }
                else
                {
                    $usuario->delete();
                    Session::flash('exito', 'Usuario eliminado con éxito.');
                }
            }
            //  Si es admin, comprobamos que no tenga pedidos y quede otro admin al menos
            else
            {
                // dd('es un usuario superadmin');
                $rolSuperAdmin = Role::where('slug', 'administrador')->pluck('id')->first();
                $usuariosSuperAdmins = DB::table('role_user')->where('role_id', $rolSuperAdmin)->count();
                
                //  Si se cumple esto ya vemos si borramos el usuairo o lo ocultamos
                if($usuariosSuperAdmins >= 2)
                {
                    if($usuario->pedidos->count() >= 1)
                    {
                        $usuario->activo = false;
                        $usuario->save();
                        Session::flash('exito', 'El usuario ya ha realizado pedido y por lo tanto no se puede eliminar. Se ha desactivado para que no se pueda volver a usar.');
                    }
                    else
                    {
                        $usuario->delete();
                        Session::flash('exito', 'Usuario eliminado con éxito.');
                    }
                }
                else
                {
                    Session::flash('error', 'No se puede borrar este usuario super administrador, ya que no hay otro superadmin y siempre debe de haber al menos uno');
                }
            }
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'Ha ocurrido un errro. Inténtelo más tarde o consulte con el administrador');
        }
        return redirect()->route('usuarios.listar');
    }

    //  Devuelve los campos para los que tiene permiso según el departamento indicado
    public function camposSegunDepartamento($usuario_id, $departamento_id)
    {
        //  Comprobamos que se están obteniendo los datos para el usuairo que lo ha solicitado
        $query = DB::select(
            "SELECT DISTINCT(campos.id), campos.nombre
            FROM departamentos, campo_departamento, campos, usuario_puede_departamento_campo
            where campo_departamento.departamento_id = departamentos.id
            and campos.id = campo_departamento.campo_id
            and usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id
            and usuario_puede_departamento_campo.usuario_id =" . $usuario_id . " and campo_departamento.departamento_id = " . $departamento_id .
            " ORDER BY campos.id"
        );

        $respuesta = [
            'campos' => $query,
            'estado' => 'exito'
        ];

        return response()->json($respuesta);
    }

    //  Devuelve los departamentos para los que tiene permiso según el campo indicado
    public function departamentosSegunCampo($usuario_id, $campo_id)
    {
        //  Comprobamos que se están obteniendo los datos para el usuairo que lo ha solicitado
        $query = DB::select(
            "SELECT DISTINCT(departamentos.id), departamentos.nombre
            FROM departamentos, campo_departamento, campos, usuario_puede_departamento_campo
            where campo_departamento.departamento_id = departamentos.id
            and campos.id = campo_departamento.campo_id
            and usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id
            and usuario_puede_departamento_campo.usuario_id =" . $usuario_id . " and campo_departamento.campo_id = " . $campo_id .
            " ORDER BY departamentos.id"
        );

        $respuesta = [
            'departamentos' => $query,
            'estado' => 'exito'
        ];

        return response()->json($respuesta);
    }

    //  Permite indicar para qué departamentos y campos tiene permiso el usuario
    public function editardepartamentoscampos(User $usuario)
    {
        $departamentos = Departamento::where('activo', true)->orderBy('nombre', 'asc')->get();
        $departamentosCampos = CampoDepartamento::orderBy('departamento_id', 'asc')->get();
        return view('usuarios.editardepartamentoscampos', compact('usuario', 'departamentos', 'departamentosCampos'));
    }

    //  Actualiza para qué departamento y campo el usuario tiene permisos para operar
    public function actualizardepartamentoscampos(User $usuario, Request $request)
    {
        //  Recupermos las parejas departamento-campo para los que el usuario va a tener permisos
        $permisos = $request->input('permisos');
        // dd($permisos);
        
        try {
            //  Recupermos los objetos
            // $departamentosCampos = CampoDepartamento::find($permisos);
            
            //  Para hacer la sincronización primero borramos todos los que tenía configurado y a continuación insertaremos todos los permisos
            $usuario->tienePermisoSobreDepartamentoCampo()->delete();

            foreach ($permisos as $permiso)
            {
                UsuarioPermisoSobreDepartamentoCampo::create(['usuario_id' => $usuario->id, 'campo_departamento_id' => $permiso]);
            }

            Session::flash('exito', 'Datos actualizados correctamente');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'Ha ocurrido un errro. Inténtelo más tarde o consulte con el administrador');
        }

        return redirect()->route('usuarios.verdetalles', $usuario);
    }

    //  Muestra los pedidos del usuario
    public function mispedidos()
    {
        $usuario = Auth::user();

        $pedidos = $usuario->pedidos;
        // dd($pedidos);
        $titulo = "Mis pedidos";
        $tipoPedidos = 'mis_pedidos';

        return view('pedidos.listarpedidos', compact('pedidos', 'titulo', 'tipoPedidos'));
    }
}

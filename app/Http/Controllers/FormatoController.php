<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Formato;
use App\LineaPedido;
use Illuminate\Support\Facades\Session; //  Para poder mandar los mensajes de session para mostrar notificaciones

class FormatoController extends Controller
{
    //	Muestra el formulario para crear un nuevo formato
    public function crear()
    {
    	return view('formatos.crear');
    }

    //	Crear un nuevo formato con los datos pasados desde el formulario
    public function guardar(Request $request)
    {
    	//	Validamos los datos que nos llegan
    	$datos = request()->validate([
    		'nombre' => 'required|unique:formatos',
    	], [
    		'nombre.required' => 'Debe de indicar un nombre para el formato',
    		'nombre.unique' => 'Ya hay dado de alta un formato con este nombre',
    	]);

    	try {
    		//	Guardamos la categoría en la BBDD
    		Formato::create([
    			'nombre' => $datos['nombre'],
    		]);

    		Session::flash('exito', 'Formato dado de alta con éxito');
    	} catch (\Exception $e) {
    		dd($e);
    		Session::flash('error', 'No se ha podido guardar el formato, pruebe más tarde o hable con el administrador');
    	}

    	return redirect()->route('formatos.listar');
    }

    //	Lista todos los formatos datos de alta en la base de datos
    public function listar()
    {
    	$formatos = Formato::orderBy('id')->get();

    	return view('formatos.listar', compact('formatos'));
    }

    //	Muestra el formulario para poder editar los datos de un formato
    public function editar(Formato $formato)
    {
    	return view('formatos.editar', compact('formato'));
    }

    //	Actualiza el formato con los datos pasados desde el formulario
    public function actualizar(Formato $formato, Request $request)
    {
    	$datos;

    	//	Sólo actualizaremos si realmente le han cambiado el nombre
    	if(strtolower($formato->nombre) != strtolower($request['nombre']))
    	{
    		// dd('diferentes');
	    	$datos = request()->validate([
	    		'nombre' => 'required|unique:formatos',
	    	], [
	    		'nombre.required' => 'Debe de indicar un nombre para el formato',
    			'nombre.unique' => 'Ya hay dado de alta un formato con este nombre',
	    	]);
    	}
    	//	Es posible que el usuario quiera cambiar algunas letras mayúsculas por minúsculas, en este caso no tenemos que pasar la regla unique
    	else
    	{
    		$datos = request()->validate([
	    		'nombre' => 'required',
	    	], [
	    		'nombre.required' => 'Debe de indicar un nombre para el formato',
	    	]);
    	}

    	try {
    		//	Actualizamos los datos del formato
    		$formato->nombre = $datos['nombre'];
    		$formato->save();

    		Session::flash('exito', 'Datos del formato actualizados');
    	} catch (\Exception $e) {
    		dd($e);
    		Session::flash('error', 'No se ha podido guardar el formato, pruebe más tarde o hable con el administrador');
    	}

    	return redirect()->route('formatos.listar');
    }

    //  Nos muestra los detalles del formato y nos permite editarlo, ocultarla, borrarlo, etc
    public function detalles(Formato $formato)
    {   
        return view('formatos.detallesformato', compact('formato'));
    }

    /// Borra una categoría si no ha sido usada. En caso contrario la desactivará para que no pueda volver a ser usada
    public function borrar(Formato $formato)
    {
        try {
            //  Si se ha hecho algún pedido con esta categoría la ocultamos
            $vecesUsado = LineaPedido::where('formato_id', $formato->id)->count();
            // dd($vecesUsado);
            if($vecesUsado >= 1)
            {
                $formato->activo = false;
                $formato->save();
                Session::flash('exito', 'La categoría ha sido usada previamente. Para no perder información la hemos desactivado para que no pueda ser usada.');
            }
            else
            {
                $formato->delete();
                Session::flash('exito', 'La formato ha sido eliminada con éxito.');
            }
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'Ha ocurrido un problema, consulte con el administrador.');
        }
        return redirect()->route('formatos.listar');
    }
}

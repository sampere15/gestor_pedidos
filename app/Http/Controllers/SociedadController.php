<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sociedad;
use App\Campo;
use Illuminate\Support\Facades\Session; //  Para poder mandar los mensajes de session para mostrar notificaciones

class SociedadController extends Controller
{
    //	Lista las categorías que hay dadas de alta en la BBDD
    public function listar()
    {
    	$sociedades = Sociedad::orderBy('id')->get();

    	return view('sociedades.listar', compact('sociedades'));
    }

    //	Muestra formulario para crear una nueva sociedad
    public function crear()
    {
    	return view('sociedades.crear');
    }

    //	Crear una nueva categoría con los datos que se pasan desde el formulario
    public function guardar(Request $request)
    {
    	//	Validamos los datos que nos llegan
    	$datos = request()->validate([
    		'nombre' => 'required',
    		'cif' => 'required|unique:sociedades',
    	], [
    		'nombre.required' => 'Debe de indicar el nombre de la sociedad',
    		'cif.required' => 'Debe de indicar el CIF de la sociedad',
    		'cif.unique' => 'Ya hay dada de alta una sociedad con este CIF',
    	]);

    	try {
    		//	Guardamos la categoría en la BBDD
    		Sociedad::create([
    			'nombre' => $datos['nombre'],
    			'cif' => $datos['cif'],
    		]);

    		Session::flash('exito', 'Socidad guardada con éxito');
    	} catch (\Exception $e) {
    		dd($e);
    		Session::flash('error', 'No se ha podido guardar la socidad, pruebe más tarde o hable con el administrador');
    	}

    	return redirect()->route('sociedades.listar');
    }

    //	Muestra el formulario para poder cambiar los datos de una sociedad
    public function editar(Sociedad $sociedad)
    {
    	return view('sociedades.editar', compact('sociedad'));
    }

    //	Actualiza los datos de una categoría con los datos pasados desde el formulario
    public function actualizar(Sociedad $sociedad, Request $request)
    {
    	$datos;

        //  Sólo actualizaremos si realmente le han cambiado el nombre
        if(strtolower($sociedad->cif) != strtolower($request['cif']))
        {
            //	Validamos los datos que nos llegan
            $datos = request()->validate([
            	'nombre' => 'required',
            	'cif' => 'required|unique:sociedades',
            ], [
            	'nombre.required' => 'Debe de indicar el nombre de la sociedad',
            	'cif.required' => 'Debe de indicar el CIF de la sociedad',
            	'cif.unique' => 'Ya hay dada de alta una sociedad con este CIF',
            ]);
        }
        //  Es posible que el usuario quiera cambiar algunas letras mayúsculas por minúsculas, en este caso no tenemos que pasar la regla unique
        else
        {
            //	Validamos los datos que nos llegan
            $datos = request()->validate([
            	'nombre' => 'required',
            	'cif' => 'required',
            ], [
            	'nombre.required' => 'Debe de indicar el nombre de la sociedad',
            	'cif.required' => 'Debe de indicar el CIF de la sociedad',
            ]);
        }

        try {
            //  Actualizamos los datos del sociedad
            $sociedad->nombre = $datos['nombre'];
            $sociedad->cif = $datos['cif'];
            $sociedad->save();

            Session::flash('exito', 'Datos de la sociedad actualizados');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'No se ha podido guardar la sociedad, pruebe más tarde o hable con el administrador');
        }

        return redirect()->route('sociedades.listar');
    }

    //  Muestra los detalles de una sociedad, tanto sus datos como campos que gestiona
    public function verdetalles(Sociedad $sociedad)
    {
        return view('sociedades.detallessociedad', compact('sociedad'));
    }

    //  Permite editar/indicar qué campos gestion una sociedad
    public function editarcampos(Sociedad $sociedad)
    {
        $campos = Campo::all();
        return view('sociedades.editarcampos', compact('sociedad', 'campos'));
    }

    //  Actualiza los campos que gestiona una sociedad
    public function actualizarcampos(Sociedad $sociedad, Request $request)
    {
        // dd($sociedad, $request->all());
        $array_campos = $request['campos'];

        // dd($array_campos);

        $campos = Campo::find($array_campos);

        // dd($campos);

        try {
            //  Actualizamos los campos que gestiona la sociedad
            $sociedad->campos()->sync($campos);
            Session::flash('exito', 'Campos actualizados correctamente');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'No se han podido actualizar los campos que gestiona la sociedad');            
        }

        return redirect()->route('sociedades.verdetalles', $sociedad);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\LineaPedido;
use Illuminate\Support\Facades\Session; //  Para poder mandar los mensajes de session para mostrar notificaciones

class CategoriaController extends Controller
{
    //	Lista las categorías que hay dadas de alta en la BBDD
    public function listar()
    {
    	$categorias = Categoria::orderBy('id')->get();

    	return view('categorias.listar', compact('categorias'));
    }

    //	Muestra formulario para crear una nueva categoría
    public function crear()
    {
    	return view('categorias.crear');
    }

    //	Crear una nueva categoría con los datos que se pasan desde el formulario
    public function guardar(Request $request)
    {
    	//	Validamos los datos que nos llegan
    	$datos = request()->validate([
    		'nombre' => 'required|unique:categorias',
    	], [
    		'nombre.required' => 'Debe de indicar un nombre para la categoría',
    		'nombre.unique' => 'Ya hay dada de alta una categoría con este nombre',
    	]);

    	try {
    		//	Guardamos la categoría en la BBDD
    		Categoria::create([
    			'nombre' => $datos['nombre'],
    		]);

    		Session::flash('exito', 'Categoría dada de alta con éxito');
    	} catch (\Exception $e) {
    		dd($e);
    		Session::flash('error', 'No se ha podido guardar la categoría, pruebe más tarde o hable con el administrador');
    	}

    	return redirect()->route('categorias.listar');
    }

    //	Muestra el formulario para poder cambiar los datos de una categoría
    public function editar(Categoria $categoria)
    {
    	return view('categorias.editar', compact('categoria'));
    }

    //	Actualiza los datos de una categoría con los datos pasados desde el formulario
    public function actualizar(Categoria $categoria, Request $request)
    {
    	$datos;

        //  Sólo actualizaremos si realmente le han cambiado el nombre
        if(strtolower($categoria->nombre) != strtolower($request['nombre']))
        {
            // dd('diferentes');
            $datos = request()->validate([
                'nombre' => 'required|unique:categorias',
            ], [
                'nombre.required' => 'Debe de indicar un nombre para la categoria',
                'nombre.unique' => 'Ya hay dado de alta una categoria con este nombre',
            ]);
        }
        //  Es posible que el usuario quiera cambiar algunas letras mayúsculas por minúsculas, en este caso no tenemos que pasar la regla unique
        else
        {
            $datos = request()->validate([
                'nombre' => 'required',
            ], [
                'nombre.required' => 'Debe de indicar un nombre para la categoria',
            ]);
        }

        try {
            //  Actualizamos los datos del categoria
            $categoria->nombre = $datos['nombre'];
            $categoria->save();

            Session::flash('exito', 'Datos de la categoria actualizados');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'No se ha podido guardar la categoria, pruebe más tarde o hable con el administrador');
        }

        return redirect()->route('categorias.listar');
    }

    //  Nos muestra los detalles de la categoría y nos permite editarla, ocultarla, borrarla, etc
    public function detalles(Categoria $categoria)
    {   
        return view('categorias.detallescategoria', compact('categoria'));
    }

    /// Borra una categoría si no ha sido usada. En caso contrario la desactivará para que no pueda volver a ser usada
    public function borrar(Categoria $categoria)
    {
        try {
            //  Si se ha hecho algún pedido con esta categoría la ocultamos
            $vecesUsado = LineaPedido::where('categoria_id', $categoria->id)->count();
            // dd($vecesUsado);
            if($vecesUsado >= 1)
            {
                $categoria->activo = false;
                $categoria->save();
                Session::flash('exito', 'La categoría ha sido usada previamente. Para no perder información la hemos desactivado para que no pueda ser usada.');
            }
            else
            {
                $categoria->delete();
                Session::flash('exito', 'La categoria ha sido eliminada con éxito.');
            }
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'Ha ocurrido un problema, consulte con el administrador.');
        }
        return redirect()->route('categorias.listar');
    }
}

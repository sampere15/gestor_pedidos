<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; //  Para poder mandar los mensajes de session para mostrar notificaciones
use App\Direccion;
use App\Campo;

class DireccionController extends Controller
{
	//  Muestra el formulario para crear una nueva dirección para un campo
    public function crear(Campo $campo)
    {
        return view('direcciones.crear', compact('campo'));
    }

    //  Guerda una nueva dirección para un campo con los datos pasados desde el formulario
    public function guardar(Campo $campo, Request $request)
    {
        // $datosTodos = $request->all();

        //  Validamos que los datos que nos han pasado
        $datos = request()->validate([
            'nombre' => 'required',
            'calle' => 'required',
            'ciudad' => 'required',
            'codigo_postal' => 'required|numeric',
            'provincia' => 'required',
            'pais' => 'required',
            'persona_contacto' => 'required',
            'numero_contacto' => 'required|numeric',
        ], [
            'nombre.required' => 'Debe indicar un nombre a la dirección. Sirve para identificarla',
            'calle.required' => 'La calle es un campo obligatorio',
            'ciudad.required' => 'La ciudad es un campo obligatorio',
            'codigo_postal.required' => 'El código postal es un campo obligatorio',
            'codigo.postal.numeric' => 'El código postal tiene que ser numérico',
            'pais.required' => 'El país es un campo obligatorio',
            'persona_contacto.required' => 'Debe de haber una persona de contacto',
            'numero_contacto.required' => 'Debe indicar un número de contacto',
            'numero_contacto.numeric' => 'El campo número de contacto tiene que ser numérico'
        ]);

        try {
            Direccion::create([
                'nombre' => $datos['nombre'],
                'calle' => $datos['calle'],
                'ciudad' => $datos['ciudad'],
                'codigo_postal' => $datos['codigo_postal'],
                'provincia' => $datos['provincia'],
                'pais' => $datos['pais'],
                'persona_contacto' => $datos['persona_contacto'],
                'numero_contacto' => $datos['numero_contacto'],
                'campo_id' => $campo->id,
            ]);

            Session::flash('exito', 'Se ha creado la dirección correctamente.');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'Ha ocurrido un error, inténtelo más tarde o póngase en contacto con el administrador.');
        }

        return redirect()->route('campos.verdetalles', $campo);
    }

    //	Muestra el formulario para editar una direccion
    public function editar(Direccion $direccion)
    {
    	$campos = Campo::all();
    	return view('direcciones.editar', compact('direccion', 'campos'));
    }

    //	Actualiza los datos de la dirección con los datos que vienen del formulario
    public function actualizar(Direccion $direccion, Request $request)
    {
    	$datosTodos = $request->all();
    	$cambioCampo = false;				//	Variable para controlar si la dirección pertenece a otro campo

        //  Validamos que los datos que nos han pasado
        $datos = request()->validate([
            'nombre' => 'required',
            'calle' => 'required',
            'ciudad' => 'required',
            'codigo_postal' => 'required|numeric',
            'provincia' => 'required',
            'pais' => 'required',
            'persona_contacto' => 'required',
            'numero_contacto' => 'required|numeric',
            'selectCampo' => 'required',
        ], [
            'nombre.required' => 'Debe indicar un nombre a la dirección. Sirve para identificarla',
            'calle.required' => 'La calle es un campo obligatorio',
            'ciudad.required' => 'La ciudad es un campo obligatorio',
            'codigo_postal.required' => 'El código postal es un campo obligatorio',
            'codigo.postal.numeric' => 'El código postal tiene que ser numérico',
            'pais.required' => 'El país es un campo obligatorio',
            'persona_contacto.required' => 'Debe de haber una persona de contacto',
            'numero_contacto.required' => 'Debe indicar un número de contacto',
            'numero_contacto.numeric' => 'El campo número de contacto tiene que ser numérico',
            'selectCampo' => 'La dirección debe estar asociada a un campo',
        ]);

        try {
        	//	Actualizamos los datos de la direccion
        	$direccion->nombre = $datos['nombre'];
        	$direccion->calle = $datos['calle'];
        	$direccion->ciudad = $datos['ciudad'];
        	$direccion->codigo_postal = $datos['codigo_postal'];
        	$direccion->provincia = $datos['provincia'];
        	$direccion->pais = $datos['pais'];
        	$direccion->persona_contacto = $datos['persona_contacto'];
        	$direccion->numero_contacto = $datos['numero_contacto'];
        	$direccion->campo_id = $datos['selectCampo'];

        	$direccion->save();

        	Session::flash('exito', 'Dirección actualizada correctamente');

        } catch (\Exception $e) {
        	dd($e);
        	Session::flash('error', 'Ha ocurrido un error, inténtelo más tarde o contacte con el administrador');
        }

        return redirect()->route('campos.verdetalles', $direccion->campo);
    }

    //  Borrar una dirección de entrega de un campo
    public function borrar(Direccion $direccion)
    {
        $direcciones = Direccion::where('campo_id', $direccion->campo_id)->where("activo", true)->get();

        /*  Para poder borrar una dirección debe cumplir:
                - El campo tiene que tener al menos 2 direcciones, para quedarse con una
                - No se debe de haber echo ningún pedido a esa dirección, si no, se perderían los datos
                - Si hay al menos dos direcciones y se ha hecho pedido a la que queremos borrar, lo que vamos a hacer es ocultarla
                * Si no, mensaje de error indicando que sólo hay una dirección y no puede ser eliminada
        */
        //  Que al menos tenga el campo 2 direcciones
        if(count($direcciones) >= 2)
        {
            $numPedidos = $direccion->pedidos->count();

            //  Comprobamos si se ha hecho algún pedido con esta direccion
            if($numPedidos >=1) //  ocultamos la direccion
            {
                $direccion->activo = false;
                $direccion->save();
                Session::flash('exito', 'Esta dirección ha sido usada. En vez de eliminada ha sido ocultada para que no se pueda volver a utilizar');
            }
            else    //  borramos la direccion
            {
                $direccion->delete();
                Session::flash('exito', 'Direccion eliminada con éxito.');
            }
        }
        else
        {
            Session::flash('error', 'No se puede eliminar la direccion, el campo sólo tiene una. El campo tiene que tener al menos una dirección. Añada una nueva dirección antes de eliminar esta.');
        }
        return redirect()->route('campos.verdetalles', $direccion->campo);
    }

    //  Muestra los detalles de la dirección
    public function detalles(Direccion $direccion)
    {
        return view('direcciones.detallesdireccion', compact('direccion'));
    }
}

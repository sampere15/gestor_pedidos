<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;
use App\Pedido;
use Illuminate\Support\Facades\Session;	//	Para poder mandar los mensajes de session para mostrar notificaciones

class ProveedorController extends Controller
{
	//	Muestra el formulario para crear un nuevo proveedor
	public function crear()
	{
		return view('proveedores.crear');
	}

	//	Crear un nuevo proveedor con los datos pasados desde el formulario
	public function guardar(Request $request)
	{
		//	Validamos los datos
		$datos = request()->validate([
			'nombre' => 'required',
			'cif' => 'max:9|nullable',
			'direccion' => 'nullable',
			'provincia' => 'nullable',
			'pais' => 'nullable',
			'persona_contacto' => 'nullable',
			'correo_contacto' => 'email|nullable',
			'telefono_contacto' => 'numeric|nullable',
		], [
			'nombre.required' => 'El necesario indicar el nombre del proveedor',
			'cif.max' => 'No utilice "-" o espacios entre la letra y los números. Ejemplo 1234678A, A12345678',
			'correo_contacto.email' => 'Debe indicar un correo válido',
			'telefono_contacto.numeric' => 'Debe indicar un teléfono válido',
		]);

		try {
			//	Guardamos el proveedor en la BBDD
			Proveedor::create([
				'nombre' => $datos['nombre'],
				'cif' => $datos['cif'],
				'direccion' => $datos['direccion'],
				'provincia' => $datos['provincia'],
				'pais' => $datos['pais'],
				'persona_contacto' => $datos['persona_contacto'],
				'correo_contacto' => $datos['correo_contacto'],
				'telefono_contacto' => $datos['telefono_contacto'],
			]);

			Session::flash('exito', 'Proveedor dado de alta correctamente');
		} catch (\Exception $e) {
			dd($e);
			Session::flash('error', 'Ha ocurrido un error, inténtelo más tarde o consulte con el administrador');
		}

		return redirect()->route('proveedores.crear');
	}

	//	Lista los proveedores dados de alta en el sistema
    public function listar()
    {
    	$proveedores = Proveedor::orderBy('nombre', 'asc')->get();

    	return view('proveedores.listar', compact('proveedores'));
    }

    //	Muestra los detalles del proveedor
    public function verdetalles(Proveedor $proveedor)
    {
    	return view('proveedores.detallesproveedor', compact('proveedor'));
    }

    //	Muestra el formulario para poder editar los datos del proveedor
    public function editar(Proveedor $proveedor)
    {
    	return view('proveedores.editar', compact('proveedor'));
    }

    //	Actualiza los datos del proveedor con los datos que nos llegan desde el formulario
    public function actualizar(Proveedor $proveedor, Request $request)
    {
    	//	Validamos los datos
		$datos = request()->validate([
			'nombre' => 'required',
			'cif' => 'max:9|nullable',
			'direccion' => 'nullable',
			'provincia' => 'nullable',
			'pais' => 'nullable',
			'persona_contacto' => 'nullable',
			'correo_contacto' => 'email|nullable',
			'telefono_contacto' => 'numeric|nullable',
		], [
			'nombre.required' => 'El necesario indicar el nombre del proveedor',
			'cif.max' => 'No utilice "-" o espacios entre la letra y los números. Ejemplo 1234678A, A12345678',
			'correo_contacto.email' => 'Debe indicar un correo válido',
			'telefono_contacto.numeric' => 'Debe indicar un teléfono válido',
		]);

		try {
			//	Actualizamos los datos del proveedor
			$proveedor->nombre = $datos['nombre'];
			$proveedor->cif = $datos['cif'];
			$proveedor->direccion = $datos['direccion'];
			$proveedor->provincia = $datos['provincia'];
			$proveedor->pais = $datos['pais'];
			$proveedor->persona_contacto = $datos['persona_contacto'];
			$proveedor->correo_contacto = $datos['correo_contacto'];
			$proveedor->telefono_contacto = $datos['telefono_contacto'];
			$proveedor->save();

			Session::flash('exito', 'Datos del proveedor actualizados correctamente');
		} catch (\Exception $e) {
			dd($e);
			Session::flash('error', 'Ha ocurrido un error, inténtelo más tarde o consulte con el administrador');
		}

		return redirect()->route('proveedores.verdetalles', $proveedor);
    }

    //	Elimina un proveedor de la BBDD
    public function borrar(Proveedor $proveedor)
    {
    	try {
    		//	Comprobamos si se ha realizado algún pedido a este proveedor. Para ver si lo eliminamos o lo ocultamos
    		$numPedidos = Pedido::where('proveedor_id', $proveedor->id)->count();

    		if($numPedidos >= 1)
    		{
    			$proveedor->activo = false;
    			$proveedor->save();
    			Session::flash('exito', 'Ya se han realizado pedidos a este proveedor y por lo tanto no se puede eliminar. Se ha procedido a desactivarlo para que no se le puedan realizar más pedidos.');
    		}
    		else
    		{
    			$proveedor->delete();
    			Session::flash('exito', 'Proveedor eliminar con éxito.');
    		}
    	} catch (\Exception $e) {
    		dd($e);
    		Session::flash('error', 'Ha ocurrido un problema. Intétenlo más tarde o consulte con el administrador');
    	}
    	return redirect()->route('proveedores.listar');
    }
}

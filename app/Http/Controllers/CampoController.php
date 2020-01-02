<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campo;
use App\Sociedad;
use App\Direccion;
use App\Departamento;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session; //  Para poder mandar los mensajes de session para mostrar notificaciones
use Illuminate\Support\Facades\DB;      //  Para poder hacer transacciones a la BBDD

class CampoController extends Controller
{
    //  Permite crar un nuevo camp
    public function crear()
    {
        $sociedades = Sociedad::all();
        return view('campos.crear', compact('sociedades'));
    }

    //  Guarda en la bbdd el campo con los datos pasasos desde el formulario si estos son correctos
    public function guardar(Request $request)
    {
        $datosTodos = $request->all();  //  guardamos todos los datos ya que no todos los vamos a validar
        // dd($datosTodos);

        $datos = request()->validate([
            'nombre' => 'required',
            'abreviatura' => 'required',
            'sociedades' => 'required',
            //  Aquí la validacion de los campos de la direccion
            'nombre' => 'required',
            'calle' => 'required',
            'ciudad' => 'required',
            'codigo_postal' => 'required|numeric',
            'provincia' => 'required',
            'pais' => 'required',
            'persona_contacto' => 'required',
            'numero_contacto' => 'required|numeric',
        ],[
            'nombre.required' => 'El campo nombre es obligatorio',
            'abreviatura.required' => 'El campo abreviatura es obligatorio',
            'sociedades.required' => 'Un campo tiene que estar gestionado al menos por una sociedad',
            //  Aquí la parte de la direccion
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
            //  Recuperamos todas las sociedades para añadírselas al campo
            $sociedades = Sociedad::find($datos['sociedades']);

            $campo = new Campo();
            $campo->nombre = $datos['nombre'];
            $campo->abreviatura = $datos['abreviatura'];
            //  Por defecto dejamos como sociedad favorita la primera que tengamos, más tarde el usuario la podrá cambiar
            $campo->sociedad_favorita_id = $sociedades[0]->id;
            
            //  Creamos la dirección para luego añadírsela al campo
            $direccion = new Direccion();
            $direccion->nombre = $datos['nombre'];
            $direccion->calle = $datos['calle'];
            $direccion->ciudad = $datos['ciudad'];
            $direccion->codigo_postal = $datos['codigo_postal'];
            $direccion->provincia = $datos['provincia'];
            $direccion->pais = $datos['pais'];
            $direccion->persona_contacto = $datos['persona_contacto'];
            $direccion->numero_contacto = $datos['numero_contacto'];
            $direccion->campo_id = $campo->id;
            
            DB::transaction(function() use ($campo, $sociedades, $direccion)
            {
                $campo->save();                     //  Guardamos el campo
                $direccion->campo_id = $campo->id;  //  Una vez el campo guardado, se lo indicamos a la dirección
                $direccion->save();
                $campo->direcciones()->save($direccion);
                $campo->sociedades()->sync($sociedades);
            });

            Session::flash('exito', 'Se ha creado el campo con éxito');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'No se ha podido guardar el campo, pruebe más tarde o hable con el administrador');
        }

        return redirect()->route('campos.crear');
    }

    public function listar()
    {
        $campos = Campo::all();

        return view('campos.listar', compact('campos'));
    }

    //  Permite editar los datos de un campo
    public function editar(Campo $campo)
    {
        // $sociedades = Sociedad::all();
        $sociedades = $campo->sociedades;
        return view('campos.editar', compact('campo', 'sociedades'));
    }

    //  Permite ver los detalles de un campo, tal como sus direcciones, pedidos, usuarios, etc
    public function verdetalles(Campo $campo)
    {
        // dd($campo);
        return view('campos.detallescampo', compact('campo'));
    }

    //  Actualiza los datos del campo con los datos pasados desde el formulario
    public function actualizar(Campo $campo, Request $request)
    {
        $datosTodos = $request->all();  //  guardamos todos los datos ya que no todos los vamos a validar

        $datos = request()->validate([
            'nombre' => 'required',
            'abreviatura' => 'required',
        ],[
            'nombre.required' => 'El campo nombre es obligatorio',
            'abreviatura.required' => 'El campo abreviatura es obligatorio',
        ]);

        try {
            $campo->nombre = $datos['nombre'];
            $campo->abreviatura = $datos['abreviatura'];
            $campo->sociedad_favorita_id = $datosTodos['selectSociedad'];

            $campo->save();

            Session::flash('exito', 'Se han actualizado los datos del campo con éxito');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'No se han podido actualizar los datos, pruebe más tarde o hable con el administrador');
        }

        return redirect()->route('campos.listar');
    }

    //  Nos devuelve de un campo su sociedad favorita y su id
    public function sociedadfavoritaysociedades(Campo $campo)
    {
    	$sociedadFavorita = $campo->sociedadFavorita;
    	
    	$respuesta = [
    		'sociedad_favorita_id' => $sociedadFavorita->id,
    		'sociedades' => $campo->sociedades,
    	];

    	return response()->json($respuesta);
    }

    //  Devuelve las direcciones de un campo
    public function direcciones(Campo $campo)
    {
        $direcciones = Direccion::where('campo_id', $campo->id)->where('activo', true)->get();

        $respuesta = [
            'direcciones' => $direcciones,
        ];

    	return response()->json($respuesta);
    }

    //  Devuelve los departamentos de un campo
    public function departamentos(Campo $campo)
    {
        $respuesta = [
            'departamentos' => $campo->departamentos,
        ];

        return response()->json($respuesta);
    }

    //  Permite indicar que sociedades gestionan el campo
    public function editarsociedades(Campo $campo)
    {
        $sociedades = Sociedad::all();

        return view('campos.editarsociedades', compact('campo', 'sociedades'));
    }

    //  Actualiza las sociedades que gestiona este campo con los datos que nos vienen desde el formulario
    public function actualizarsociedades(Campo $campo)
    {
        $datos = request()->validate([
            'sociedades' => 'required',
        ],[
            'sociedades.required' => 'Un campo tiene que estar gestionado al menos por una sociedad',
        ]);

        try {
            $sociedades = Sociedad::find($datos['sociedades']);
            // dd($campo, $sociedades);

            //  Por defecto dejamos como sociedad favorita la primera que tengamos, más tarde el usuario la podrá cambiar
            $campo->sociedad_favorita_id = $sociedades[0]->id;
            
            //  Recuperamos todas las sociedades para añadírselas al campo
            
            DB::transaction(function() use ($campo, $sociedades)
            {
                $campo->save();
                $campo->sociedades()->sync($sociedades);
            });

            Session::flash('exito', 'Se han actualizado las sociedades que gestionan este campo');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'No se han podido actualizar las sociedades, pruebe más tarde o hable con el administrador');
        }

        return redirect()->route('campos.verdetalles', $campo);
    }

    //  Actualiza los departamentos que tiene un campo
    public function editardepartamentos(Campo $campo)
    {
        $departamentos = Departamento::where('activo', true)->get();

        return view('campos.editardepartamentos', compact('campo', 'departamentos'));
    }

    public function actualizardepartamentos(Campo $campo)
    {
        $datos = request()->validate([
            'departamentos' => 'required',
        ], [
            'departamentos.required' => 'Un campo debe de tener al menos un departamento',
        ]);

        try {
            //  Recuperamos los departamentos seleccionados
            $departamentos = Departamento::find($datos['departamentos']);
            
            //  Ahora vamos a sincronizar los departamentos que tiene este campo. Añadirá/borrará los departamentos necesarios
            $campo->departamentos()->sync($departamentos);

            Session::flash('exito', 'Departamentos actualizados correctamente');
        } catch (\Exception $e) {
            dd($e);
            Session::flash('error', 'Ha ocurrido un problema, consulte con el administrador');
        }

        return redirect()->route('campos.verdetalles', $campo);
    }
}

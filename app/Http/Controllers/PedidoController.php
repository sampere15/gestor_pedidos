<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;	//	Para poder crear nosotros las respuestas con JSON
use App\Pedido;
use App\EstadoPedido;
use App\LineaPedido;
use App\HistoricoEstadoPedido;
use Illuminate\Support\Facades\Auth;
use App\Campo;
use App\Proveedor;
use App\Categoria;
use App\Formato;
use App\Descripcion;
use App\Sociedad;
use App\EstadoLinea;
use App\User;
use Illuminate\Support\Facades\DB;		//	Para poder hacer transacciones a la BBDD
use Illuminate\Support\Facades\Session;	//	Para poder mandar los mensajes de session para mostrar notificaciones
use PDF;                                //  Para poder generar archivos PDF's
use App\Helpers\MiHelper;

class PedidoController extends Controller
{
	//	Muestra la vista que permite crear un nuevo pedido
    public function crear()
    {
    	$proveedores = Proveedor::where('activo', true)->orderBy('nombre', 'asc')->get();
    	$categorias = Categoria::where('activo', true)->orderBy('nombre')->get();						//	Recuperamos las categorias
    	$formatos = Formato::where('activo', true)->orderBy('nombre')->get();							//	Recuperamos los formatos
    	$usuario = Auth::user();
        
        $departamentos = $usuario->departamentosConPermiso();
        $campos = $usuario->camposConPermiso();

    	return view('pedidos.crear', compact('proveedores', 'categorias', 'formatos', 'usuario', 'departamentos', 'campos'));
    }

    //	Guarda el pedido que ha rellenado el cliente con las líneas de pedido
    public function guardar(Request $request, $guardadoTemporal = false)
    {   
        //	Recogemos todos los datos
        $datosPedidoTodo = $request->all();
        $indiceLineaPedido = 1;
        $arrayLineasPedido = array();		//	Aqui vamos a guardar todas las líneas de pedido para luego adjuntarlas al pedido
        $estadoPedidoSolicitado = EstadoPedido::where('nombre', 'solicitado')->firstOrFail();	//	Estado que tendrán los pedidos
        $estadoPedidoEnCreacion = EstadoPedido::where('nombre', 'en_creacion')->firstOrFail();   //  Si guardamos el pedido para seguir después
        
        //  Validamos los datos del pedido que nos llegan desde el formulario
        $datosPedido = request()->validate([
            'proveedor_id' => 'required',
            'campoSelect' => 'required',
            'sociedadSelect' => 'required',
            'direccionSelect' => 'required',
            'departamentoSelect' => 'required',
        ],[
            'proveedor_id.required' => 'Debe indicar un proveedor',
            'campoSelect.required' => 'Debe seleccionar un campo',
            'sociedadSelect.required' => 'Debe seleccionar una sociedad',
            'direccionSelect.required' => 'Debe seleccionar una dirección',
            'departamentoSelect.required' => 'Debe seleccionar un departamento',
        ]);
        
        
        //  Creamos una instancia del pedido y rellenamos sus propiedades
        $pedido = new Pedido();
        $pedido->usuario_realiza_pedido_id = Auth::user()->id;
        $pedido->fecha_pedido = null;
        $pedido->proveedor_id = $datosPedido["proveedor_id"];
        $pedido->campo_id = $datosPedido["campoSelect"];
        $pedido->departamento_id = $datosPedido["departamentoSelect"];
        $pedido->sociedad_id = $datosPedido["sociedadSelect"];
        $pedido->direccion_id = $datosPedido["direccionSelect"];
        $pedido->total_pedido = $datosPedidoTodo["inputTotalPedido"];        
        
        //  El pedido lo podemos generar y dejarlo a la espera de aprovación o guardarlo y continuar más tarde
        if($guardadoTemporal)
            $pedido->estado_pedido_id = $estadoPedidoEnCreacion->id;
        else
            $pedido->estado_pedido_id = $estadoPedidoSolicitado->id;
        
        //  Comprobamos si se han marcado estados especiales en el pedido
        if(array_key_exists('cbEstadosEspaciales', $datosPedidoTodo))
        {
            if(array_key_exists('rbEstadosEspeciales', $datosPedidoTodo))
            {
                if($datosPedidoTodo['rbEstadosEspeciales'] == 'rbSolicitadoProveedor')
                    $pedido->solicitado_al_proveedor = true;
                else if($datosPedidoTodo['rbEstadosEspeciales'] == 'rbMaterialRecibido')
                    $pedido->ya_recibido = true;
            }
        }

        if($datosPedidoTodo['observaciones'] != null)
            $pedido->observaciones = $datosPedidoTodo['observaciones'];

        //	Vamos a recorrer los datos para saber cuantas líneas de pedido nos llegan
        foreach ($datosPedidoTodo as $datos => $valor) 
        {
        	//	Como se pueden añadir o quitar líneas de pedido no sabemos que numeración van a tener "selectCategoria1, selectCategoria2..."
        	//	Entonces cada vez que encontremos un selectCategoria vemos con que número viene y obtenemos los datos de esa línea con una función
        	if(str_contains($datos, "selectCategoria"))
        	{
        		//	A la función en este caso le pasaríamos todos los datos de la request y por ejemplo un 2. Así sabe que tiene que sacar los datos de 
        		//	selectCategoria2, inputPrecio2, etc....
                // $lineaPedido = $this->ExtraerDatosLineaPedido($datosPedidoTodo, $indiceLineaPedido, substr($datos, strlen($datos)-1), $pedido->ya_recibido);
                $lineaPedido = $this->ExtraerDatosLineaPedido($datosPedidoTodo, $indiceLineaPedido, $pedido->ya_recibido, false);

        		//	Antes de hacer el push comprobamos que las líneas cumplen la validación. Para ellos creamos un objeto request y lo pasamos a validar
    			$request = new Request([
    				'numero_linea' => $lineaPedido->numero_linea,
    				'categoria_id' => $lineaPedido->categoria_id,
    				'descripcion' => $lineaPedido->descripcion,
    				'unidades' => $lineaPedido->unidades,
    				'formato_id' => $lineaPedido->formato_id,
    				'precio' => $lineaPedido->precio,
    			]);

    			$this->validate($request, [
    				'numero_linea' => 'required',
    				'categoria_id' => 'required',
    				'descripcion' => 'required|max:255',
    				'unidades' => 'required|numeric',
    				'formato_id' => 'required',
    				'precio' => 'required|numeric',
    			], [
    				'descripcion.required' => 'Una de las líneas de pedido no tiene descripción',
    				'unidades.required' => 'Debe indicar las unidades',
    				'unidades.numeric' => 'Las unidades deben de ser en formato numérico',
    				'precio.required' => 'Debe indicar el precio',
    				'precio.numeric' => 'El precio debe de estar en formato numérico',
    			]);

    			array_push($arrayLineasPedido, $lineaPedido);

        		$indiceLineaPedido++;	//	Aumentamos el contador de líneas de pedido
        	}
        }

        $guardado = false;  //  Indica si se ha guardado correctamente el pedido
        $estado = "";       //  El estado que pasaremos a Session::flash
        $mensaje = "";      //  El mensaje que pasaremos a Session::flash

		//	Por si ocurre cualquier error a la hora de guardar el pedido en la BBDD, esta operación la vamos a hacer a través de na transacción
		try
        {
            DB::transaction(function() use ($pedido, $arrayLineasPedido) 
            {
                $pedido->save();    //  Guardamos el pedido

                //  Guardamos las líneas de pedido. Es decir, añade/quita las necesarias
                $pedido->lineasPedido()->saveMany($arrayLineasPedido);
            });

            //  Si todo OK mandamos los mensajes de sesión
            $estado = 'exito';
            if($guardadoTemporal)
            {
                $mensaje = 'Pedido guardado con éxito. Podrá terminarlo más tarde. Lo puede encontrar en la sección "Mis pedidos guardados';
                $estadoPedido = 'en_creacion';
            }
            else
            {
                $mensaje = 'Pedido guardado con éxito. Está en estado de solicitado';
                $estadoPedido = 'solicitado';
            }
            $guardado = true;


            $historial_guardado = $this->ActualizarHistorial($pedido, $estadoPedido);


            //  Grabamos el histórico de este pedido
            // if(!$this->ActualizarHistorial($pedido, $estadoPedido))
            if(!$historial_guardado)
            {

                $estado = 'aviso';
                $mensaje = 'Pedido guardado con éxito, pero ha ocurrido un problema al actualizar el histórico del pedido. Contacte con el administrador';
            }

        } 
        catch (\Exception $e) 
        {
            dd($e);
            $estado = 'error';
            $mensaje = 'No se ha podido guardar el pedido, compruebe que no haya ningún error';
        }

		
        //  Mostramos el resultado de la operación
        Session::flash($estado, $mensaje);
        
	    return redirect()->route('pedidos.crear');
    }

    //  Permite guardar un pedido en estado de creación para continuarlo más tarde
    public function guardartemporal(Request $request)
    {
        $guardadoTemporal = true;   //  Con esto indicamos que no estamos creando el pedido, si no, guardándolo para seguir más tarde
        $this->guardar($request, $guardadoTemporal);
        return redirect()->route('pedidos.crear');
    }

    //  Permite editar un pedido
    public function editar(Pedido $pedido)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            if(Auth::user()->isRole('administrador') || $pedido->estadoPedido->nombre == "solicitado" || $pedido->estadoPedido->nombre == "en_creacion")
            {
                $proveedores = Proveedor::all();                        //  Recuperamos los proveedores
                $categorias = Categoria::all();                         //  Recuperamos las categorias
                $formatos = Formato::all();                             //  Recuperamos los formatos
                $usuario = Auth::user();
                $totalPedido = $pedido->total_pedido;
                $departamentos = $usuario->departamentosConPermiso();
                $campos = $usuario->camposConPermiso();
    
                return view('pedidos.editar', compact('pedido', 'departamentos', 'campos', 'proveedores', 'categorias', 'formatos', 'descripciones', 'usuario', 'sociedades', 'totalPedido'));
            }
            else
            {
                return back();
            }
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //  Actualiza los datos del pedido
    public function Actualizar(Pedido $pedido, Request $request)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->isRole('administrador') || $pedido->estadoPedido->nombre == "solicitado" || $pedido->estadoPedido->nombre == "en_creacion")
        {
            //  Recogemos todos los datos
            $datosPedidoTodo = $request->all();
            $indiceLineaPedido = 1;
            $arrayLineasPedido = array();       //  Aqui vamos a guardar todas las líneas de pedido para luego adjuntarlas al pedido
            $estadoSolicitado = EstadoPedido::where('nombre', 'solicitado')->first();
            $estadoEnCreacion = EstadoPedido::where('nombre', 'en_creacion')->first();

            //  Validamos los datos del pedido que nos llegan desde el formulario
            $datosPedido = request()->validate([
                'proveedor_id' => 'required',
                'campoSelect' => 'required',
                'sociedadSelect' => 'required',
                'direccionSelect' => 'required',
                'departamentoSelect' => 'required',
            ],[
                'proveedor_id.required' => 'Debe indicar un proveedor',
                'campoSelect.required' => 'Debe seleccionar un campo',
                'sociedadSelect.required' => 'Debe seleccionar una sociedad',
                'direccionSelect.required' => 'Debe seleccionar una dirección',
                'departamentoSelect.required' => 'Debe seleccionar un departamento',
            ]);

            //  Vamos a recorrer los datos para saber cuantas líneas de pedido nos llegan
            foreach ($datosPedidoTodo as $datos => $valor)
            {
                //  Como se pueden añadir o quitar líneas de pedido no sabemos que numeración van a tener "selectCategoria1, selectCategoria2..."
                //  Entonces cada vez que encontremos un selectCategoria vemos con que número viene y obtenemos los datos de esa línea con una función
                if(str_contains($datos, "selectCategoria"))
                {
                    //  A la función en este caso le pasaríamos todos los datos de la request y por ejemplo un 2. Así sabe que tiene que sacar los datos de 
                    //  selectCategoria2, inputPrecio2, etc....

                    //  Extraemos su índice de línea
                    // $indice = explode("selectCategoria", );
                    // dd($indice);
                    // $lineaPedido = $this->ExtraerDatosLineaPedido($datosPedidoTodo, $indiceLineaPedido, substr($datos, strlen($datos)-1), $pedido->ya_recibido);
                    // dd($datosPedidoTodo);
                    $lineaPedido = $this->ExtraerDatosLineaPedido($datosPedidoTodo, $indiceLineaPedido, $pedido->ya_recibido, true);

                    //  Antes de hacer el push comprobamos que las líneas cumplen la validación. Para ellos creamos un objeto request y lo pasamos a validar
                    $request = new Request([
                        'numero_linea' => $lineaPedido->numero_linea,
                        'categoria_id' => $lineaPedido->categoria_id,
                        'descripcion' => $lineaPedido->descripcion,
                        'unidades' => $lineaPedido->unidades,
                        'formato_id' => $lineaPedido->formato_id,
                        'precio' => $lineaPedido->precio,
                    ]);

                    $this->validate($request, [
                        'numero_linea' => 'required',
                        'categoria_id' => 'required',
                        'descripcion' => 'required|max:255',
                        'unidades' => 'required|numeric',
                        'formato_id' => 'required',
                        'precio' => 'required|numeric',
                    ], [
                        'descripcion.required' => 'Una de las líneas de pedido no tiene descripción',
                        'unidades.required' => 'Debe indicar las unidades',
                        'unidades.numeric' => 'Las unidades deben de ser en formato numérico',
                        'precio.required' => 'Debe indicar el precio',
                        'precio.numeric' => 'El precio debe de estar en formato numérico',
                    ]);

                    array_push($arrayLineasPedido, $lineaPedido);

                    $indiceLineaPedido++;   //  Aumentamos el contador de líneas de pedido
                }
            }

            //  Actualizamos el pedido con los datos que nos llegan desde el formulario
            $pedido->proveedor_id = $datosPedido["proveedor_id"];
            $pedido->campo_id = $datosPedido["campoSelect"];
            $pedido->departamento_id = $datosPedido["departamentoSelect"];
            $pedido->sociedad_id = $datosPedido["sociedadSelect"];
            $pedido->direccion_id = $datosPedido["direccionSelect"];
            $pedido->observaciones = $datosPedidoTodo['observaciones'];
            $pedido->total_pedido = $datosPedidoTodo['inputTotalPedido'];

            //  Comprobamos si sigue en creación (porque lo ha guardado) o pasa a estar solicitado
            if($datosPedidoTodo['tramitarPedido'] == "true")
            {
                //  En el caso de que estamos solicitando un pedido que teníamos guardado le cambiamos el estado y creamos una entrada en el historial
                if($pedido->estadoPedido->nombre == "en_creacion")
                {
                    $pedido->estado_pedido_id = $estadoSolicitado->id;
                    $this->ActualizarHistorial($pedido, $estadoSolicitado->nombre);
                }
            }
            else if($datosPedidoTodo['tramitarPedido'] == "false")
            {
                $pedido->estado_pedido_id = $estadoEnCreacion->id;
            }

            //  Comprobamos si se han marcado estados especiales en el pedido
            if(array_key_exists('cbEstadosEspaciales', $datosPedidoTodo))
            {
                if(array_key_exists('rbEstadosEspeciales', $datosPedidoTodo))
                {
                    if($datosPedidoTodo['rbEstadosEspeciales'] == 'rbSolicitadoProveedor')
                    {
                        $pedido->solicitado_al_proveedor = true;
                        $pedido->ya_recibido = false;
                    }
                    else if($datosPedidoTodo['rbEstadosEspeciales'] == 'rbMaterialRecibido')
                    {
                        $pedido->ya_recibido = true;
                        $pedido->solicitado_al_proveedor = false;
                    }
                }
            }
            else
            {
                $pedido->ya_recibido = false;
                $pedido->solicitado_al_proveedor = false;
            }

            try
            {
                DB::transaction(function() use ($pedido, $arrayLineasPedido) 
                {
                    $pedido->save();    //  Guardamos el pedido
                    $pedido->lineasPedido()->delete();  //  Eliminamos las líneas de pedido para luego guardar las "buenas"

                    //  Guardamos las líneas de pedido. Es decir, añade/quita las necesarias
                    $pedido->lineasPedido()->saveMany($arrayLineasPedido);
                });

                //  Si todo OK mandamos los mensajes de sesión
                Session::flash('exito', 'Pedido actualizado con éxito');
            } 
            catch (\Exception $e) 
            {
                Session::flash('error', 'Ha ocurrido un error mientras se actualizaba el pedido');
            }

            return redirect()->route('pedidos.verdetalles', $pedido);
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //	Extrae la línea pedido con el índice indicado
    // private function ExtraerDatosLineaPedido($datosPedido, $indiceLineaPedido, $indice, $pedido_ya_recibido)
    private function ExtraerDatosLineaPedido($datosPedido, $indiceLineaPedido, $pedido_ya_recibido, $editando)
    {
    	//	Creamos una nueva linea y la rellenamos con los datos del pedido. Los datos los extrae de la request que nos viene con los datos del form
    	// $linea = new LineaPedido();
    	// $linea->numero_linea = $indiceLineaPedido;
    	// $linea->descripcion = $datosPedido["inputDescripcion" . $indice];
    	// $linea->unidades = $datosPedido["inputUnidades" . $indice];
    	// $linea->precio = round($datosPedido["inputPrecio" . $indice], 2);
    	// $linea->categoria_id = $datosPedido["selectCategoria" . $indice];
        // $linea->formato_id = $datosPedido["selectFormato" . $indice];

        // dd($datosPedido);

        $linea = new LineaPedido();
    	$linea->numero_linea = $indiceLineaPedido;
    	$linea->descripcion = $datosPedido["inputDescripcion" . $indiceLineaPedido];
    	$linea->unidades = $datosPedido["inputUnidades" . $indiceLineaPedido];
    	$linea->precio = round($datosPedido["inputPrecio" . $indiceLineaPedido], 2);
    	$linea->categoria_id = $datosPedido["selectCategoria" . $indiceLineaPedido];
        $linea->formato_id = $datosPedido["selectFormato" . $indiceLineaPedido];

        //  Recuperamos el estado de la líena de pedido si es que tiene
        if($editando && array_key_exists("estado_linea_" . $indiceLineaPedido, $datosPedido))
        {
            $linea->estado_linea_id = $datosPedido["estado_linea_" . $indiceLineaPedido];
        }
        
        //  Si se ha marcado que el pedido ya se ha recibido, marcaremos las líneas como recibidas
        if($pedido_ya_recibido)
        {
            $estadoLineaRecibida_id = EstadoLinea::where("nombre", "recibida")->pluck("id")->first();
            $linea->estado_linea_id = $estadoLineaRecibida_id;
        }

    	return $linea;
    }

    //  Recupera de la request todas las lineas que nos llegan desde el formulario de material recibido
    private function RecuperarLineasActualizarMarialRecibida($arrayDatos, $pedidoId)
    {
        $arrayLineas = array();

        //  Vamos a extraer cada una de las líneas
        for($i = 1; $i <= $arrayDatos["numeroLineas"]; $i++)
        {
            $linea = new LineaPedido();
            $linea->pedido_id = $pedidoId;
            $linea->numero_linea = $i;
            $linea->categoria_id = $arrayDatos["categoriaLineaId" . $i];
            $linea->descripcion = $arrayDatos["descripcionLinea" . $i];
            $linea->unidades = $arrayDatos["unidadesLinea" . $i];
            $linea->formato_id = $arrayDatos["formatoLineaId" . $i];
            $linea->precio = $arrayDatos["precioLinea" . $i];
            $linea->estado_linea_id = $arrayDatos["selectEstadoLinea" . $i];

            array_push($arrayLineas, $linea);
        }

        return $arrayLineas;

    }

    //  Cuando se recepciona mercancía actualizamos el estado de las líneas del pedido
    public function actualizarmaterialrecibido(Pedido $pedido, Request $request)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            $datos = $request->all();

            $arrayLineasPedido = $this->RecuperarLineasActualizarMarialRecibida($request->all(), $pedido->id);

            $finalizado = false;
            $parcialmente = false;
            $pendiente = false;

            //  Recuperamos los diferentes estados en los que puede estar una línea, para así saber en qué estado se encuentra el pedido
            $estadoParcialId = EstadoLinea::where("nombre", "parcial")->pluck("id")->first();
            $estadoRecibidaId = EstadoLinea::where("nombre", "recibida")->pluck("id")->first();
            $estadoPendienteId = EstadoLinea::where("nombre", "pendiente")->pluck("id")->first();

            //  Comprobar si todas las líneas están recibidas. Si es así, marcar pedido como finalizado
            foreach ($arrayLineasPedido as $linea) 
            {
                if($linea->estado_linea_id == $estadoParcialId)
                {
                    $parcialmente = true;
                    break;
                }
                else if($linea->estado_linea_id == $estadoRecibidaId)
                {
                    $finalizado = true;
                }
                else if($linea->estado_linea_id == $estadoPendienteId)
                {
                    $pendiente = true;
                }
            }

            //  Una vez que ya hemos recuperado todas las líneas y actualizado su estado, procedemos a guardarlas en la BBDD mediante una transacción
            try 
            {
                DB::transaction(function() use ($pedido, $arrayLineasPedido, $finalizado, $parcialmente, $pendiente) 
                {
                    if($parcialmente)
                    {
                        Session::flash('exito', 'Estado de las líneas actualizadas con éxito. Pedido marcado como recibido parcialmente');
                        $this->ActualizarHistorial($pedido, "recibido_parcialmente");
                        $estado = EstadoPedido::where('nombre', 'recibido_parcialmente')->first();
                        $pedido->estado_pedido_id = $estado->id;
                    }
                    else if($pendiente && !$parcialmente && !$finalizado)
                    {
                        Session::flash('exito', 'Estado de las líneas actualizadas con éxito');
                        $this->ActualizarHistorial($pedido, "pendiente_recibir");   
                        $estado = EstadoPedido::where('nombre', 'pendiente_recibir')->first();
                        $pedido->estado_pedido_id = $estado->id;
                    }
                    else if(!$pendiente && !$parcialmente && $finalizado)
                    {
                        $this->ActualizarHistorial($pedido, "finalizado");
                        Session::flash('exito', 'Todas las líneas han sido recibidas. Pedido marcado como finalizado');   
                        $estado = EstadoPedido::where('nombre', 'finalizado')->first();
                        $pedido->estado_pedido_id = $estado->id;
                    }
                    else if($finalizado && ($pendiente || $parcialmente))
                    {
                        Session::flash('exito', 'Estado de las líneas actualizadas con éxito. Pedido marcado como recibido parcialmente');
                        $this->ActualizarHistorial($pedido, "recibido_parcialmente");
                        $estado = EstadoPedido::where('nombre', 'recibido_parcialmente')->first();
                        $pedido->estado_pedido_id = $estado->id;
                    }

                    $pedido->save();

                    //  Borramos todas las líneas de pedido y guardamos las nuevas
                    $pedido->lineasPedido()->delete();
                    $pedido->lineasPedido()->saveMany($arrayLineasPedido);

                    Session::flash('exito', 'Actualizado estado material recibido con éxito');
                });
            } 
            catch (\Exception $e) 
            {
                dd($e);
                Session::flash('error', 'Ha ocurrido un error al actualizar el estado de las líneas de pedido. Inténtelo más tarde o contacto con el administrador');  
            }

            return redirect()->route('pedidos.verdetalles', $pedido);
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //	Actualiza el histórico del pedido al estado indicado
    private function ActualizarHistorial($pedido, $estado)
    {
    	try 
    	{

			$usuario = Auth::user();	//	Recuperamos el usuario que ha hecho la validación

			//	Registramos el nuevo cambio de estado de estado
			HistoricoEstadoPedido::create([
				'pedido_id' => $pedido->id,
				'estado' => $estado,
				'usuario_id' => $usuario->id,
				'fecha' => date("Y-m-d H:i:s"),
            ]);


			return true;
    	} 
    	catch (\Exception $e) {
    			return false;
    	}
    }

    //	Muestra la lista de los pedidos que están pendientes de ser revisados
    public function listarsolicitados()
    {
        $pedidos = Auth::user()->pedidosSegunEstado('solicitado');

		//	La misma vista la vamos autilizar para mostrar las diferentes listas de pedidos, así que pasamos string para especificar en el título que estamos viendo
		$titulo = 'Lista de pedidos solicitados';
		$tipoPedidos = 'solicitados';

    	return view('pedidos.listarpedidos', compact('pedidos', 'titulo', 'tipoPedidos'));
    }

    //	Muestra los detalles de un pedido específico, con sus líneas de pedido, etc.
    public function verdetalles(Pedido $pedido)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            //	Recuperamos todas las líneas que tenga el pedido
            $lineasPedido = LineaPedido::where('pedido_id', $pedido->id)->with('Formato', 'Categoria')->get();
            //	Vamos a calcular el total del pedido
            $totalPedido = $pedido->total_pedido;
    
            $estados = EstadoPedido::orderBy('id')->where('nombre', '<>', 'en_creacion')->get();
    
            return view('pedidos.detallespedido', compact('pedido', 'lineasPedido', 'totalPedido', 'estados'));
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //	Permite consultar el histórico de un pedido, viendo cuando ha cambiado de estado, en qué fecha y por qué usuario
    public function verhistorico(Pedido $pedido)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento->id, $pedido->campo_id))
        {
            $pedido_id = $pedido->id;	//	Guardamos el ID del pedido
            //	Recuperamos el histórico del pedido, con los datos del usuario y ordenado por ID de histórico, para tenerlos en orden cronológico
            $historicoEstados = HistoricoEstadoPedido::where('pedido_id', $pedido->id)->with('usuario')->orderBy('id', 'asc')->get();
    
            return view('pedidos.historicoestadospedido', compact('pedido_id', 'historicoEstados'));
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //	Esta función no está protegida con el Middleware \App\Http\Middleware\VerifyCsrfToken::class, por lo tanto comprobamos que el usuario esté autenticado
    public function validar(Request $request, Pedido $pedido)
    {        
        $usuario = Auth::user();

        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if($usuario->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            if($pedido->estadoPedido->nombre == "solicitado")
            {
                $mensaje = "";
                try 
                {
                    $estadoValidado = EstadoPedido::where('nombre', 'validado')->first();   //  Recuperamos el nuevo estado

                    //  Lo validamos
                    $pedido->estado_pedido_id = $estadoValidado->id;
                    $pedido->save();

                    //  Registramos el nuevo cambio de estado de estado
                    HistoricoEstadoPedido::create([
                        'pedido_id' => $pedido->id,
                        'estado' => $estadoValidado->nombre,
                        'usuario_id' => $usuario->id,
                        'fecha' => date("Y-m-d H:i:s"),
                    ]);

                    //  Si es ajax preparamos el mensaje de exito
                    if($request != null && $request->ajax())
                    {
                        //  Prapramos el mensaje de respuesta que enviaremos a la vista
                        $mensaje = [
                            'mensaje' => 'Pedido ' . $pedido->id . ' validado correctamente',
                            'estado' => 'Exito'
                        ];
                    }
                    else
                    {
                        Session::flash('exito', 'Pedido validado correctamente');
                    }
                } 
                catch (\Exception $e) 
                {
                    //  Si es ajax preparamos el mensaje de error
                    if($request != null && $request->ajax())
                    {
                        $mensaje = [
                            'mensaje' => 'No se ha podido validar el pedido, contacta con el administrador',
                            'estado' => 'Error',
                        ];  
                    }
                    else
                    {
                        Session::flash('error', 'No se ha podido validar el pedido, contacta con el administrador');
                    }
                }

                //  Si se ha hecho la petición Ajax desde el listado de pedidos hacemos una respuesta json
                if($request->ajax())
                    return response()->json($mensaje);
                //  Si se ha hecho la petición desde los detalles del pedido, hacemos una respuesta http con un mensaje flash
                else
                    return redirect()->route('pedidos.verdetalles', $pedido);
            }
            else
            {
                return back();
            }
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //  Función que valida varios pedidos
    public function validarvarios(Request $request)
    {
        //  Recuperamos los pedidos que vamos a validar
        $pedidos = Pedido::find($request->input("pedidos"));

        try
        {
            $estadoValidado = EstadoPedido::where('nombre', 'validado')->first();   //  Recuperamos el nuevo estado
            
            //  Preparamos una transacción ya que vamos a modificar varios pedidos
            DB::beginTransaction();

            foreach($pedidos as $pedido)
            {
                //  Lo validamos
                $pedido->estado_pedido_id = $estadoValidado->id;
                $pedido->save();

                //  Registramos el nuevo cambio de estado de estado
                HistoricoEstadoPedido::create([
                    'pedido_id' => $pedido->id,
                    'estado' => $estadoValidado->nombre,
                    'usuario_id' => Auth::user()->id,
                    'fecha' => date("Y-m-d H:i:s"),
                ]);
            }

            DB::commit();

            Session::flash('exito', 'Pedidos validados correctamente');

            return redirect()->route("pedidos.listarsolicitados");
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            dd($e);

            Session::flash('error', 'Ha ocurrido un error, contacta con el administrador');
        }
    }

    //  Función que cursa varios pedidos
    public function cursarvarios(Request $request)
    {
        //  Recuperamos los pedidos que vamos a validar
        $pedidos = Pedido::find($request->input("pedidos"));

        try
        {
            $estadoValidado = EstadoPedido::where('nombre', 'cursado')->first();   //  Recuperamos el nuevo estado
            
            //  Preparamos una transacción ya que vamos a modificar varios pedidos
            DB::beginTransaction();

            foreach($pedidos as $pedido)
            {
                //  Lo validamos
                $pedido->estado_pedido_id = $estadoValidado->id;
                $pedido->save();

                //  Registramos el nuevo cambio de estado de estado
                HistoricoEstadoPedido::create([
                    'pedido_id' => $pedido->id,
                    'estado' => $estadoValidado->nombre,
                    'usuario_id' => Auth::user()->id,
                    'fecha' => date("Y-m-d H:i:s"),
                ]);
            }

            DB::commit();

            Session::flash('exito', 'Pedidos cursados correctamente');

            return redirect()->route("pedidos.listarvalidados");
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            dd($e);

            Session::flash('error', 'Ha ocurrido un error, contacta con el administrador');
        }
    }

    //	Lista los pedidos que estan validados
    public function listarvalidados()
    {
        $pedidos = Auth::user()->pedidosSegunEstado('validado');

		//	La misma vista la vamos autilizar para mostrar las diferentes listas de pedidos, así que pasamos string para especificar en el título que estamos viendo
		$titulo = 'Lista de pedidos validados';
		$tipoPedidos = 'validados';

    	return view('pedidos.listarpedidos', compact('pedidos', 'titulo', 'tipoPedidos'));
    }

    //	Lista los pedidos que están cursados
    public function listarcursados()
    {
        $pedidos = Auth::user()->pedidosSegunEstado('cursado');

		//	La misma vista la vamos autilizar para mostrar las diferentes listas de pedidos, así que pasamos string para especificar en el título que estamos viendo
		$titulo = 'Lista de pedidos cursados';
		$tipoPedidos = 'cursados';

    	return view('pedidos.listarpedidos', compact('pedidos', 'titulo', 'tipoPedidos'));
    }

    //  Lista los pedidos que están cursados
    public function listarpendientes()
    {
        $array_estados = ['pendiente_recibir', 'recibido_parcialmente'];
        $pedidos = Auth::user()->pedidosSegunEstados($array_estados);

        //  La misma vista la vamos autilizar para mostrar las diferentes listas de pedidos, así que pasamos string para especificar en el título que estamos viendo
        $titulo = 'Lista de pedidos pendientes de recibir';
        $tipoPedidos = 'pendientes';

        return view('pedidos.listarpedidos', compact('pedidos', 'titulo', 'tipoPedidos'));
    }

    public function listarpendientescomunicar()
    {
        $array_estados = ['cursado', 'pendiente_recibir', 'recibido_parcialmente', 'finalizado'];
        $pedidos = Auth::user()->pedidosSegunEstados($array_estados);

        //  Ahora de los que hemos recuperado con ese estado, sólo nos quedamos con los que estén pedientes de comunicar al proveedor
        $pedidos = $pedidos->where('pedido_comunicado', false);        
        
        $titulo = 'Lista de pedidos pendientes de comunicar al proveedor';
        $tipoPedidos = 'sin_comunicar';
        return view('pedidos.listarpedidos', compact('pedidos', 'titulo', 'tipoPedidos'));
    }

    //	Maraca un pedido como cursado
    public function cursar(Request $request, Pedido $pedido)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id) && !$pedido->cancelado)
        {
            if($pedido->estadoPedido->nombre == "validado")
            {
                $mensaje = "";
                $estado;        //  Aquí guardaremos el estado del pedido dependiendo del caso en el que nos encontremos

                try 
                {
                    $usuario = Auth::user();    //  Recuperamos el usuario que ha hecho la validación
                    $cursado_nombre = EstadoPedido::where('nombre', 'cursado')->pluck('nombre')->first();
                    $pendiente_nombre = EstadoPedido::where('nombre', 'pendiente_recibir')->pluck('nombre')->first();
                    $fecha = date("Y-m-d H:i:s");

                    //  Dependiendo de si ya se ha solicitado al proveedor, el pedido pasará a cursado para que Virginia avise al proveedor o pasará a pendiente de recibir
                    if($pedido->solicitado_al_proveedor)
                    {
                        $estado = EstadoPedido::where('nombre', 'pendiente_recibir')->first();

                        //  Va a pasara pendiente de recibir, pero también 
                        HistoricoEstadoPedido::create([
                            'pedido_id' => $pedido->id,
                            'estado' => $cursado_nombre,
                            'usuario_id' => $usuario->id,
                            'fecha' => $fecha,
                        ]);

                        if($request->ajax())
                        {
                            $mensaje = [
                                'mensaje' => 'Pedido ' . $pedido->id . ' cursado correctamente. Como el técnico ya se ha puesto en contacto con el proveedor, el pedido pasa a estar pendiente de recibir',
                                'estado' => 'Exito'
                            ];
                        }
                        else
                        {
                            Session::flash('exito', 'Pedido cursado correctamente. Como el técnico ya se ha puesto en contacto con el proveedor, el pedido pasa a estar pendiente de recibir');
                        }
                    }
                    else if($pedido->ya_recibido)
                    {
                        $estado = EstadoPedido::where('nombre', 'finalizado')->first();

                        //  Va a pasara pendiente de recibir, pero también 
                        HistoricoEstadoPedido::create([
                            'pedido_id' => $pedido->id,
                            'estado' => $cursado_nombre,
                            'usuario_id' => $usuario->id,
                            'fecha' => $fecha,
                        ]);

                        //  Va a pasara pendiente de recibir, pero también 
                        HistoricoEstadoPedido::create([
                            'pedido_id' => $pedido->id,
                            'estado' => $pendiente_nombre,
                            'usuario_id' => $usuario->id,
                            'fecha' => $fecha,
                        ]);

                        if($request->ajax())
                        {
                            $mensaje = [
                                'mensaje' => 'Pedido ' . $pedido->id . ' cursado correctamente. Como el material ya se ha recibido se ha marcado el pedido como finalizado. ¡OJO! Hay que comunicar el pedido al proveedor igualmente!',
                                'estado' => 'Exito'
                            ];
                        }
                        else
                        {
                            Session::flash('exito', 'Pedido cursado correctamente. Como el material ya se ha recibido se ha marcado el pedido como finalizado. ¡OJO! Hay que comunicar el pedido al proveedor igualmente!');
                        }
                    }
                    else
                    {
                        $estado = EstadoPedido::where('nombre', 'cursado')->first();

                        if($request->ajax())
                        {
                            $mensaje = [
                                'mensaje' => 'Pedido ' . $pedido->id . ' cursado correctamente',
                                'estado' => 'Exito'
                            ];
                        }
                        else
                        {
                            Session::flash('exito', 'Pedido cursado correctamente');
                        }
                    }

                    //  Lo cursamos
                    $pedido->estado_pedido_id = $estado->id;
                    $pedido->fecha_pedido = $fecha;
                    $pedido->save();

                    //  Registramos el nuevo cambio de estado de estado
                    HistoricoEstadoPedido::create([
                        'pedido_id' => $pedido->id,
                        'estado' => $estado->nombre,
                        'usuario_id' => $usuario->id,
                        'fecha' => $fecha,
                    ]);
                } 
                catch (\Exception $e) 
                {
                    if($request->ajax())
                    {
                        $mensaje = [
                            'mensaje' => 'No se ha podido cursar el pedido, contacta con el administrador',
                            'estado' => 'Error',
                        ];  
                    }
                    else
                    {
                        Session::flash('error', 'No se ha podido cursar el pedido, contacte con el administrador');
                    }
                } 
                
                if($request->ajax())
                    return response()->json($mensaje);
                else
                    return redirect()->route('pedidos.verdetalles', $pedido);
            }
            else
            {
                return back();
            }
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //	Permite indicar que un pedido ha sido recepcionado total (finalinzado) o parcialmente
    public function materialrecibido(Pedido $pedido)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            if($pedido->estadoPedido->nombre == "cursado" || $pedido->estadoPedido->nombre == "pendiente_recibir"
                || $pedido->estadoPedido->nombre == "recibido_parcialmente")
            {
                //	Recuperamos los posibles estados de línea para pasarlos a los select
                $estadosLineas = EstadoLinea::all();
    
                return view('pedidos.materialrecibido', compact('pedido', 'estadosLineas'));
            }
            else
            {
                return back();
            }
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //  Para indicar que un pedido ha sido comunicado al proveedor
    public function comunicaraproveedor(Pedido $pedido)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            //  Por seguridad sólo dejamos comunicar los que no estén comunicados
            if($pedido->pedido_comunicado)
                return back();

            try 
            {
                DB::transaction(function() use ($pedido)
                {
                    //  Indicamos que ya ha sido comunicado
                    $pedido->pedido_comunicado = true;

                    //  Modificaremos el estado en caso de que venga por la via normal y lo ponem
                    if($pedido->estadoPedido->nombre == "cursado")
                    {
                        $estado = EstadoPedido::where('nombre', 'pendiente_recibir')->first();
                        $pedido->estado_pedido_id = $estado->id;

                        //  Registramos el nuevo cambio de estado de estado
                        HistoricoEstadoPedido::create([
                            'pedido_id' => $pedido->id,
                            'estado' => $estado->nombre,
                            'usuario_id' => Auth::user()->id,
                            'fecha' => date("Y-m-d H:i:s"),
                        ]);
                    }

                    //  Guardamos el pedido
                    $pedido->save();
                });

                //  Si todo OK...
                Session::flash('exito', 'El pedido ha sido marcado como comunicado al proveedor');
            } 
            catch (\Exception $e) 
            {
                dd($e);
                Session::flash('error', 'Ha ocurrido un error mientras se actualizaba el pedido. Inténtelo más tarde o consulte con el administrador');
            }

            return redirect()->route('pedidos.verdetalles', $pedido);
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //  Genera el documento PDF del pedido para poder enviarselo al proveedor
    public function documentopedido(Pedido $pedido)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            //  Indicamos que el documento ha sido visualizado, así se activará la opción que ya ha sido comunicado el proveedor
            $pedido->documento_visualizado = true;
            $pedido->save();
    
            $datos = [
                'pedido' => $pedido->toArray(),
                'sociedad' => $pedido->sociedad->toArray(),
                'lineasPedido' => $pedido->lineasPedido->toArray(),
                'direccion' => $pedido->direccion->toArray(),
                'proveedor' => $pedido->proveedor->nombre,
                'campo' => $pedido->campo->nombre,
            ];
    
            $aux = Pedido::where('id', $pedido->id)->with('campo', 'proveedor', 'direccion', 'sociedad')->first()->toArray();
    
            $lineas = LineaPedido::where('pedido_id', $pedido->id)->with('categoria', 'formato')->get()->toArray();
    
            $datos = ['pedido' => $aux, 'lineas' => $lineas];
            $pdf = PDF::loadView('pedidos.plantillafactura', $datos)->setPaper('a4', 'landscape');        
    
            return $pdf->stream();
            //  Este funciona bien
            // $pdf = new PDF();
            // $pdf->set_base_path("/www/public/css/");
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //  Lista todos los pedidos finalizados
    public function listarfinalizados()
    {
        $pedidos = Auth::user()->pedidosSegunEstado('finalizado');

        $titulo = 'Lista de pedidos finalizados';
        $tipoPedidos = 'finalizados';

        return view('pedidos.listarpedidos', compact('pedidos', 'titulo', 'tipoPedidos'));
    }

    //  Muestra los pedidos que hemos guardados y tenemos pendientes de finalizar
    public function mispedidosguardados()
    {
        $usuario = Auth::user();

        $pedidos = $usuario->pedidosSegunEstado('en_creacion');                 //  Recuperamos los pedidos con ese estado para los que el usuario tiene permiso
        $pedidos = $pedidos->where('usuario_realiza_pedido_id', $usuario->id);  //  Nos quedamos tan sólo con los que el usuario ha creado

        $titulo = 'Mis pedidos guardados';
        $tipoPedidos = 'en_creacion';

        return view('pedidos.listarpedidos', compact('pedidos', 'titulo', 'tipoPedidos'));
    }

    public function observaciones(Pedido $pedido)
    {
        $respuesta = [
            'observaciones' => $pedido->observaciones
        ];

        return response()->json($respuesta);
    }

    //  Borra un pedido, si el estado en el que se encuentra lo permite
    public function eliminar(Pedido $pedido, Request $request)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            try 
            {
                //  Dependiendo del estado en el que se encuentre el pedido, lo vamos a borrar directamente o cancelar
                if($pedido->estadoPedido->nombre == "solicitado" || $pedido->estadoPedido->nombre == "en_creacion")
                {
                    //  Como hay que borrar tanto el pedido como las líneas de pedido, transacción al canto
                    DB::transaction(function() use ($pedido)
                    {
                        $pedido->lineasPedido()->delete();      //  Borramos todas las líneas del pedido
                        $pedido->historicoEstados()->delete();  //  Borramos el histórico de sus estados
                        $pedido->delete();
                        Session::flash('exito', 'El pedido se ha eliminado correctamente.');
                    });
                    return redirect()->route('home');
                }
                else
                {
                    Session::flash('aviso', 'El pedido no se encuentra en un estado en el que no se puede eliminar, pruebe a cancelarlo o consulte con el administrador');
                }
            } 
            catch (\Exception $e) {
                Session::flash('error', 'Ha ocurrido un problema, contacte con el administrador');
            }

            return redirect()->route('pedidos.verdetalles', $pedido);
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //  Cancela un pedido si está ya en un punto en el que no puede ser eliminado
    public function cancelar(Pedido $pedido, Request $request)
    {
        //  Antes de nada comprobamos que el usuario tiene permiso para trabajar con el pedido con ese departamento-campo
        if(Auth::user()->comprobarDepartamentoYCampo($pedido->departamento_id, $pedido->campo_id))
        {
            try 
            {
                //  Dependiendo del estado en el que se encuentre el pedido, lo vamos a borrar directamente o cancelar
                if(!$pedido->cancelado && ($pedido->estadoPedido->nombre != "solicitado" && $pedido->estadoPedido->nombre != "en_creacion"))
                {
                    $pedido->cancelado = true;
                    if($request['inputMotivoCancelacion'] != null)
                        $pedido->motivo_cancelacion = $request['inputMotivoCancelacion'];

                    $pedido->save();

                    //  Ahora que el pedido ya ha sido cancelado mandaremos un correo a la persona que lo validó para que sea que ha sido cancelado
                    MiHelper::EnviarEmailPedidoCancelado($pedido, Auth::user());

                    Session::flash('exito', 'El pedido ha sido cancelado con éxito.');
                }
                else
                {
                    Session::flash('aviso', 'El pedido ya ha sido cancelado o no se puede modificar el estado del mismo');
                }
            } 
            catch (\Exception $e) {
                dd($e);
                Session::flash('error', 'Ha ocurrido un problema, contacte con el administrador');
            }

            return redirect()->route('pedidos.verdetalles', $pedido);
        }
        else
        {
            Session::flash('error', 'No dispone de permisos para realizar esa operación');
            return redirect()->to('/home');
        }
    }

    //  Lista todos los pedidos realizados a un proveedor
    public function listarpedidosproveedor(Proveedor $proveedor)
    {
        $pedidos = Pedido::where('proveedor_id', $proveedor->id)->orderBy('id')->get();
        $titulo = "Pedidos realizados al proveedor " . $proveedor->nombre;

        return view('pedidos.listarpedidospro', compact('pedidos', 'titulo'));
    }

    //  Lista todos los pedidos que ha hecho un usuario
    public function listarpedidosusuario(User $usuario)
    {
        //  Recuperamos todos los pedidos para los que tengo permiso
        $pedidos = Auth::user()->pedidosSegunEstados(['solicitado', 'validado', 'cursado', 'pendiente_recibir', 'recibido_parcialmente', 'finalizado']);
        //  Ahora filtramos por aquellos que ha creado el usuario
        $pedidos = $pedidos->where('usuario_realiza_pedido_id', $usuario->id);
        $titulo = "Pedidos realizados por el usuario " . $usuario->nombre;

        return view('pedidos.listarpedidospro', compact('pedidos', 'titulo'));
    }

    //  Función de ADMINISTRADOR - Recupera todos todos los pedidos
    public function listartodos()
    {
        if(Auth::user()->isRole('administrador'))
        {
            $pedidos = Pedido::orderBy('id', 'desc')->get();
            $titulo = "Todos los pedidos";
    
            return view('pedidos.listarpedidospro', compact('pedidos', 'titulo'));
        }
        else
        {
            return back();
        }
    }
}

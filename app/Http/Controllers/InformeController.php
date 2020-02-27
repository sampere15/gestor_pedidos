<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;
use App\Campo;
use App\Departamento;
use App\Sociedad;
use App\User;
use App\Categoria;
use App\Pedido;
use App\EstadoPedido;
use Carbon\Carbon;	//	para poder trabajar las fechas
use Illuminate\Support\Facades\DB;		// para las consultar RAW
use Illuminate\Support\Facades\Auth;

class InformeController extends Controller
{
    //	Muestra el formulario para poder pedir informes detallados de los gastos
    public function gastos()
    {
		$usuario = Auth::user();
    	$proveedores = Proveedor::all();
    	$departamentos = $usuario->departamentosConPermiso();
		// $departamentos = Departamento::all();
		// $campos = Campo::all();
		$campos = $usuario->camposConPermiso();
    	$sociedades = Sociedad::all();
    	// $usuarios = User::all();
    	$categorias = Categoria::all();
		// $estadosPedidos = EstadoPedido::where('nombre', '<>', 'en_creacion')->get();
		$estadosPedidos = EstadoPedido::whereIn('nombre', ["cursado", "pendiente_recibir", "recibido_parcialmente", "finalizado"])->get();

		return view('informes.formulariogastos', compact('usuario', 'proveedores', 'departamentos', 'campos', 'sociedades', 'usuarios', 'categorias', 'estadosPedidos'));
		// return view('informes.test', compact('proveedores', 'departamentos', 'campos', 'sociedades', 'usuarios', 'categorias', 'estadosPedidos'));
    }

    //	Aplica los filtros introducidos en el formulario o recupera la información de la BBDD
    public function resultadogastos(Request $request)
    {
		$usuario = Auth::user();

    	//	Recuperamos todos los filtros posibles
    	if($request->input('dateFechaInicio') != null)
    		$fInicio = Carbon::parse($request->input('dateFechaInicio'))->startOfDay();
    	else
    		$fInicio = null;

    	if($request->input('dateFechaFin') != null)
    		$fFin = Carbon::parse($request->input('dateFechaFin'))->endOfDay();
    	else
    		$fFin = null;

    	$proveedor_id = $request->input('proveedor_id');
    	$proveedor_nombre = $request->input('proveedorNombre');
    	$campo_id = $request->input('campoSelect');
    	$departamento_id = $request->input('departamentoSelect');
    	$categoria_id = $request->input('categoriaSelect');
    	$sociedad_id = $request->input('sociedadSelect');
    	$usuario_id = $request->input('usuarioSelect');
    	$estadosPedidos = $request->input('estadoPedidoSelect');

    	//	Guardamos en un array todos los filtros aplicados para volver a pasarlos
    	$filtros = [
    		'fInicio' => $request->input('dateFechaInicio'),
    		'fFin' => $request->input('dateFechaFin'),
    		'proveedor_id' => $proveedor_id,
    		'proveedor_nombre' => $proveedor_nombre,
    		'departamento_id' => $departamento_id,
    		'campo_id' => $campo_id,
    		'categoria_id' => $categoria_id,
    		'sociedad_id' => $sociedad_id,
    		'usuario_id' => $usuario_id,
    		'estados_pedidos' => $estadosPedidos,
		];

    	//	Por un lado vamos a tener la query con los datos que queremos seleccionar
    	// $query = 
    	// 	"SELECT ped.id as pedido_id, ped.fecha_pedido, prov.nombre as proveedor_nombre, prov.id as proveedor_id, depart.nombre as departamento_nombre, soc.nombre as sociedad_nombre, estado.nombre_mostrar as estado_nombre, ped.total_pedido as total_pedido, campo.nombre as campo_nombre, cat.nombre, linea.precio, linea.unidades, cat.nombre as categoria_nombre, linea.unidades as linea_unidades, linea.precio as linea_precio
    	// 	FROM pedidos ped, campos campo, proveedores prov, departamentos depart, sociedades soc, estados_pedidos estado, lineas_pedidos as linea, categorias as cat
    	// 	WHERE ped.campo_id = campo.id
    	// 	AND ped.proveedor_id = prov.id
    	// 	AND ped.departamento_id = depart.id
    	// 	AND ped.sociedad_id = soc.id
    	// 	AND ped.estado_pedido_id = estado.id
    	// 	AND ped.cancelado = false
    	// 	AND ped.id = linea.pedido_id
    	// 	AND linea.categoria_id = cat.id
		// 	AND ped.fecha_pedido IS NOT NULL
		// 	";
		
		$query = 
    		"SELECT ped.id as pedido_id, ped.fecha_pedido, prov.nombre as proveedor_nombre, prov.id as proveedor_id, depart.nombre as departamento_nombre, soc.nombre as sociedad_nombre, estado.nombre_mostrar as estadoPedido_nombre, ped.total_pedido as total_pedido, campo.nombre as campo_nombre, cat.nombre, linea.precio, linea.unidades, cat.nombre as categoria_nombre, linea.unidades as linea_unidades, linea.precio as linea_precio, estados_lineas.nombre as lineaEstado
    		FROM pedidos ped, campos campo, proveedores prov, departamentos depart, sociedades soc, estados_pedidos estado, lineas_pedidos as linea, categorias as cat, estados_lineas
    		WHERE ped.campo_id = campo.id
    		AND ped.proveedor_id = prov.id
    		AND ped.departamento_id = depart.id
    		AND ped.sociedad_id = soc.id
    		AND ped.estado_pedido_id = estado.id
    		AND ped.cancelado = false
    		AND ped.id = linea.pedido_id
    		AND linea.categoria_id = cat.id
			AND linea.estado_linea_id = estados_lineas.id
			AND ped.fecha_pedido IS NOT NULL
			AND (ped.departamento_id, ped.campo_id) IN
                (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                FROM users, usuario_puede_departamento_campo, campo_departamento
                WHERE users.id = " . $usuario->id . "
                AND users.id = usuario_puede_departamento_campo.usuario_id
                AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)
    		";

		//	Esta va a ser la segunda parte de la query dónde vamos a poner las condiciones
		$queryCondicion = "";

    	$arrayParametros = [];
    	$hayCondicion = true;

    	if($fInicio != null)
		{
			//	Cambiado en vez de filtrar por la fecha de creación vamos a tener en cuenta el campo fecha_pedido
			// $queryCondicion .= " AND ped.created_at >= ?";
			$queryCondicion .= " AND ped.fecha_pedido >= ?";
			array_push($arrayParametros, $fInicio);
		}

		if($fFin != null)
		{
			//	Cambiado en vez de filtrar por la fecha de creación vamos a tener en cuenta el campo fecha_pedido
			// $queryCondicion .= " AND ped.created_at <= ?";
			$queryCondicion .= " AND ped.fecha_pedido <= ?";
			array_push($arrayParametros, $fFin);
		}

		if($proveedor_id != null && $proveedor_id != 0)
		{
			$queryCondicion .= " AND ped.proveedor_id = ?";
			array_push($arrayParametros, $proveedor_id);
		}

		if($campo_id != null && $campo_id != 0)
		{
			$queryCondicion .= " AND ped.campo_id = ?";
			array_push($arrayParametros, $campo_id);
		}

		if($categoria_id != null && $categoria_id != 0)
		{
			$queryCondicion .= " AND linea.categoria_id = ?";
			//	Añadimos el nombre de la categoria si la ha selecionado
			$filtros['categoria_nombre'] = Categoria::find($categoria_id)->nombre;
			array_push($arrayParametros, $categoria_id);
		}

		if($sociedad_id != null && $sociedad_id != 0)
		{
			$queryCondicion .= " AND ped.sociedad_id = ?";
			array_push($arrayParametros, $sociedad_id);
		}

		if($departamento_id != null && $departamento_id != 0)
		{
			$queryCondicion .= " AND ped.departamento_id = ?";
			array_push($arrayParametros, $departamento_id);
		}

		if($estadosPedidos != null && count($estadosPedidos) >= 1)
		{
			if(count($estadosPedidos) > 1 || $estadosPedidos[0] != "0")
			{
				$queryCondicion .= " AND ped.estado_pedido_id IN (" . implode(",", $estadosPedidos) . ")";
			}
			// $queryCondicion .= " AND ped.estado_pedido_id IN (?)";
			// array_push($arrayParametros, implode(",", $estadosPedidos));
			// array_push($arrayParametros, $estadosPedidos);
		}

		$query .= $queryCondicion . " ORDER BY ped.id ASC";

		//	Ahora que ya tenemos la query la ejecutamos para obtener el resultado según los filtros
		$resultado = DB::select( DB::raw($query), $arrayParametros);

		// dd($resultado);

		//	Ahora vamos a calcular el coste total de todas las líneas seleccionadas
		$queryTotal = 
			"SELECT SUM(unidades * precio) as total_linea 
			FROM pedidos ped, lineas_pedidos linea 
			WHERE linea.pedido_id = ped.id 
			AND ped.cancelado = false 
			AND ped.fecha_pedido IS NOT NULL
			AND (ped.departamento_id, ped.campo_id) IN
                (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                FROM users, usuario_puede_departamento_campo, campo_departamento
                WHERE users.id = " . $usuario->id . "
                AND users.id = usuario_puede_departamento_campo.usuario_id
                AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)
			";

		$queryTotal .= $queryCondicion;
		$total = DB::select(DB::raw($queryTotal), $arrayParametros)[0]->total_linea;

		//	Para volver a rellenar los desplegables, etc
		$proveedores = Proveedor::all();
    	// $campos = Campo::all();
    	// $departamentos = Departamento::all();
    	$sociedades = Sociedad::all();
    	$usuarios = User::all();
    	$categorias = Categoria::all();
		// $estadosPedidos = EstadoPedido::where('nombre', '<>', 'en_creacion')->get();
		$estadosPedidos = EstadoPedido::whereIn('nombre', ["cursado", "pendiente_recibir", "recibido_parcialmente", "finalizado"])->get();

		$departamentos = $usuario->departamentosConPermiso();
		$campos = $usuario->camposConPermiso();

		// $aux = in_array(5, $filtros["estados_pedidos"]);
		// dd($aux);
		// dd($filtros);

		return view('informes.resultadogastos', compact('usuario', 'resultado', 'total', 'filtros', 'proveedores', 'campos', 'departamentos', 'sociedades', 'usuarios', 'categorias', 'estadosPedidos'));
    }

    //	Muestra el formulario para poder obtener informes de gastos agrupados por categorías
    public function porcategorias()
    {
		$usuario = Auth::user();
		$proveedores = Proveedor::all();
		$departamentos = $usuario->departamentosConPermiso();
		$campos = $usuario->camposConPermiso();
    	// $departamentos = Departamento::all();
    	// $campos = Campo::all();
		$sociedades = Sociedad::all();
    	// $usuarios = User::all();
    	// $categorias = Categoria::all();
    	// $estadosPedidos = EstadoPedido::where('nombre', '<>', 'en_creacion')->get();

    	return view('informes.formularioporcategorias', compact('usuario', 'proveedores', 'campos', 'departamentos', 'sociedades'));
    }

    //	Con los filtros aplicados en el formulario recupera los pedidos para preparar un informe
    public function resultadoporcategorias(Request $request)
    {
		$usuario = Auth::user();

    	//	Recuperamos todos los filtros posibles
    	if($request->input('dateFechaInicio') != null)
    		$fInicio = Carbon::parse($request->input('dateFechaInicio'))->startOfDay();
    	else
    		$fInicio = null;

    	if($request->input('dateFechaFin') != null)
    		$fFin = Carbon::parse($request->input('dateFechaFin'))->endOfDay();
    	else
    		$fFin = null;

    	//	Recuperamos la información introducida por el usuario
    	$proveedor_id = $request->input('proveedor_id');
    	$proveedor_nombre = $request->input('proveedorNombre');
    	$campo_id = $request->input('campoSelect');
    	$departamento_id = $request->input('departamentoSelect');
    	$categoria_id = $request->input('categoriaSelect');
    	$sociedad_id = $request->input('sociedadSelect');
    	$usuario_id = $request->input('usuarioSelect');
    	$estadosPedidos = $request->input('estadoPedidoSelect');

    	//	Guardamos en un array todos los filtros aplicados para volver a pasarlos al formulario
    	$filtros = [
    		'fInicio' => $request->input('dateFechaInicio'),
    		'fFin' => $request->input('dateFechaFin'),
    		'proveedor_id' => $proveedor_id,
    		'proveedor_nombre' => $proveedor_nombre,
    		'campo_id' => $campo_id,
    		'departamento_id' => $departamento_id,
    		'categoria_id' => $categoria_id,
    		'sociedad_id' => $sociedad_id,
    		'usuario_id' => $usuario_id,
    		'estados_pedidos' => $estadosPedidos,
    	];

    	//	Por un lado vamos a tener la query "fija" y por otro lado los posibles filtros y sus valores
    	$query = 
    		"SELECT categorias.nombre as 'categoria', sum(lineas_pedidos.precio * lineas_pedidos.unidades) as 'total_categoria'
			FROM pedidos as ped, proveedores pro, campos camp, estados_pedidos, lineas_pedidos, categorias
			WHERE ped.cancelado = false
			AND ped.fecha_pedido IS NOT NULL
			AND ped.id = lineas_pedidos.pedido_id
			AND ped.campo_id = camp.id
			AND ped.proveedor_id = pro.id
			AND lineas_pedidos.categoria_id = categorias.id
			AND ped.estado_pedido_id = estados_pedidos.id
			AND (ped.departamento_id, ped.campo_id) IN
                (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                FROM users, usuario_puede_departamento_campo, campo_departamento
                WHERE users.id = " . $usuario->id . "
                AND users.id = usuario_puede_departamento_campo.usuario_id
                AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)
			";

		//	Esta va a ser la segunda parte de la query dónde vamos a poner las condiciones
		$queryCondicion = "";

    	$arrayParametros = [];
    	$hayCondicion = true;

    	if($fInicio != null)
		{
			//	Cambiado en vez de filtrar por la fecha de creación vamos a tener en cuenta el campo fecha_pedido
			// $queryCondicion .= " AND ped.created_at >= ?";
			$queryCondicion .= " AND ped.fecha_pedido >= ?";
			array_push($arrayParametros, $fInicio);
		}

		if($fFin != null)
		{
			//	Cambiado en vez de filtrar por la fecha de creación vamos a tener en cuenta el campo fecha_pedido
			// $queryCondicion .= " AND ped.created_at <= ?";
			$queryCondicion .= " AND ped.fecha_pedido <= ?";
			array_push($arrayParametros, $fFin);
		}

		if($proveedor_id != null && $proveedor_id != 0)
		{
			$queryCondicion .= " AND ped.proveedor_id = ?";
			array_push($arrayParametros, $proveedor_id);
		}

		if($campo_id != null && $campo_id != 0)
		{
			$queryCondicion .= " AND ped.campo_id = ?";
			array_push($arrayParametros, $campo_id);
		}

		if($sociedad_id != null && $sociedad_id != 0)
		{
			$queryCondicion .= " AND ped.sociedad_id = ?";
			array_push($arrayParametros, $sociedad_id);
		}

		if($departamento_id != null && $departamento_id != 0)
		{
			$queryCondicion .= " AND ped.departamento_id = ?";
			array_push($arrayParametros, $departamento_id);
		}

		$query .= $queryCondicion . " group by categorias.nombre order by categorias.nombre";

		//	Ahora que ya tenemos la query la ejecutamos para obtener el resultado según los filtros
		$resultado = DB::select( DB::raw($query), $arrayParametros);

		//	Ahora vamos a calcular el coste total de todas las líneas seleccionadas
		$queryTotal = 
			"SELECT SUM(unidades * precio) as total_linea 
			FROM pedidos ped, lineas_pedidos linea 
			WHERE linea.pedido_id = ped.id 
			AND ped.cancelado = false 
			AND ped.fecha_pedido IS NOT NULL
			AND (ped.departamento_id, ped.campo_id) IN
                (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                FROM users, usuario_puede_departamento_campo, campo_departamento
                WHERE users.id = " . $usuario->id . "
                AND users.id = usuario_puede_departamento_campo.usuario_id
                AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)
			";
		$queryTotal .= $queryCondicion;
		$total = DB::select(DB::raw($queryTotal), $arrayParametros)[0]->total_linea;

		//	Para volver a rellenar los desplegables, etc
		$proveedores = Proveedor::all();
    	// $campos = Campo::all();
    	// $departamentos = Departamento::all();
		$sociedades = Sociedad::all();
		$departamentos = $usuario->departamentosConPermiso();
		$campos = $usuario->camposConPermiso();

		return view('informes.resultadoporcategorias', compact('usuario', 'resultado', 'total', 'filtros', 'proveedores', 'campos', 'departamentos', 'sociedades'));
	}
	
	//	Muestra el formulario para obtener un informe de líneas de pedido agrupadas por categorías
	public function lineascategorias()
	{
		$usuario = Auth::user();

    	$proveedores = Proveedor::all();
    	$departamentos = $usuario->departamentosConPermiso();
		$campos = $usuario->camposConPermiso();
    	$sociedades = Sociedad::all();
    	$categorias = Categoria::all();
		// $estadosPedidos = EstadoPedido::where('nombre', '<>', 'en_creacion')->get();
		$estadosPedidos = EstadoPedido::whereIn('nombre', ["cursado", "pendiente_recibir", "recibido_parcialmente", "finalizado"])->get();
		
		// dd($estadosPedidos);

		return view('informes.formulariolineasporcategorias', compact('usuario', 'proveedores', 'departamentos', 'campos', 'sociedades', 'categorias', 'estadosPedidos'));
		
	}

	//	Muestra el resultado del informa de líneas de pedidos agrupadas por categorías
	public function resultadolineascategorias(Request $request)
	{
		$usuario = Auth::user();

    	//	Recuperamos todos los filtros posibles
    	if($request->input('dateFechaInicio') != null)
    		$fInicio = Carbon::parse($request->input('dateFechaInicio'))->startOfDay();
    	else
    		$fInicio = null;

    	if($request->input('dateFechaFin') != null)
    		$fFin = Carbon::parse($request->input('dateFechaFin'))->endOfDay();
    	else
    		$fFin = null;

    	$proveedor_id = $request->input('proveedor_id');
    	$proveedor_nombre = $request->input('proveedorNombre');
    	$campo_id = $request->input('campoSelect');
    	$departamento_id = $request->input('departamentoSelect');
    	$categoria_id = $request->input('categoriaSelect');
    	$sociedad_id = $request->input('sociedadSelect');
    	$usuario_id = $request->input('usuarioSelect');
    	$estadosPedidos = $request->input('estadoPedidoSelect');

    	//	Guardamos en un array todos los filtros aplicados para volver a pasarlos
    	$filtros = [
    		'fInicio' => $request->input('dateFechaInicio'),
    		'fFin' => $request->input('dateFechaFin'),
    		'proveedor_id' => $proveedor_id,
    		'proveedor_nombre' => $proveedor_nombre,
    		'departamento_id' => $departamento_id,
    		'campo_id' => $campo_id,
    		'categoria_id' => $categoria_id,
    		'sociedad_id' => $sociedad_id,
    		'usuario_id' => $usuario_id,
    		'estados_pedidos' => $estadosPedidos,
		];

		$query = 
    		"SELECT ped.id as pedido_id, cat.nombre as categoria_nombre, campos.nombre as campo_nombre, prov.nombre as proveedor_nombre, SUM(linea.precio * linea.unidades) as importe
			FROM lineas_pedidos as linea, pedidos as ped, categorias as cat, campos, proveedores as prov
			WHERE linea.pedido_id = ped.id
			AND cat.id = linea.categoria_id
			AND campos.id = ped.campo_id
			AND prov.id = ped.proveedor_id
			AND ped.cancelado = false
			AND ped.fecha_pedido IS NOT NULL
			AND (ped.departamento_id, ped.campo_id) IN
                (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                FROM users, usuario_puede_departamento_campo, campo_departamento
                WHERE users.id = " . $usuario->id . "
                AND users.id = usuario_puede_departamento_campo.usuario_id
                AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)
			";

		$queryTotal = 
    		"SELECT SUM(linea.precio * linea.unidades) as importe_total
			FROM lineas_pedidos as linea, pedidos as ped, categorias as cat, campos, proveedores as prov
			WHERE linea.pedido_id = ped.id
			AND cat.id = linea.categoria_id
			AND campos.id = ped.campo_id
			AND prov.id = ped.proveedor_id
			AND ped.cancelado = false
			AND ped.fecha_pedido IS NOT NULL
			AND (ped.departamento_id, ped.campo_id) IN
                (SELECT campo_departamento.departamento_id, campo_departamento.campo_id
                FROM users, usuario_puede_departamento_campo, campo_departamento
                WHERE users.id = " . $usuario->id . "
                AND users.id = usuario_puede_departamento_campo.usuario_id
                AND usuario_puede_departamento_campo.campo_departamento_id = campo_departamento.id)
			";

		//	Para rellenar si hemos aplicado alguno o varios de los diferentes filtros
		$queryCondicion = "";
		$arrayParametros = [];

		//	Esta va a ser la segunda parte de la query dónde vamos a poner las condiciones
		$queryCondicion = "";

    	$arrayParametros = [];
    	$hayCondicion = true;

    	if($fInicio != null)
		{
			//	Cambiado en vez de filtrar por la fecha de creación vamos a tener en cuenta el campo fecha_pedido
			$queryCondicion .= " AND ped.fecha_pedido >= ?";
			array_push($arrayParametros, $fInicio);
		}

		if($fFin != null)
		{
			//	Cambiado en vez de filtrar por la fecha de creación vamos a tener en cuenta el campo fecha_pedido
			$queryCondicion .= " AND ped.fecha_pedido <= ?";
			array_push($arrayParametros, $fFin);
		}

		if($proveedor_id != null && $proveedor_id != 0)
		{
			$queryCondicion .= " AND ped.proveedor_id = ?";
			array_push($arrayParametros, $proveedor_id);
		}

		if($campo_id != null && $campo_id != 0)
		{
			$queryCondicion .= " AND ped.campo_id = ?";
			array_push($arrayParametros, $campo_id);
		}

		if($categoria_id != null && $categoria_id != 0)
		{
			$queryCondicion .= " AND linea.categoria_id = ?";
			//	Añadimos el nombre de la categoria si la ha selecionado
			$filtros['categoria_nombre'] = Categoria::find($categoria_id)->nombre;
			array_push($arrayParametros, $categoria_id);
		}

		if($sociedad_id != null && $sociedad_id != 0)
		{
			$queryCondicion .= " AND ped.sociedad_id = ?";
			array_push($arrayParametros, $sociedad_id);
		}

		if($departamento_id != null && $departamento_id != 0)
		{
			$queryCondicion .= " AND ped.departamento_id = ?";
			array_push($arrayParametros, $departamento_id);
		}

		if($estadosPedidos != null && count($estadosPedidos) >= 1)
		{
			if(count($estadosPedidos) > 1 || $estadosPedidos[0] != "0")
			{
				$queryCondicion .= " AND ped.estado_pedido_id IN (" . implode(",", $estadosPedidos) . ")";
			}
		}

		$query .= $queryCondicion . " GROUP BY ped.id, cat.nombre ORDER BY ped.id";
		$queryTotal .= $queryCondicion;

		//	Ahora que ya tenemos la query la ejecutamos para obtener el resultado según los filtros
		$resultado = DB::select( DB::raw($query), $arrayParametros);
		$total = DB::select(DB::raw($queryTotal), $arrayParametros)[0]->importe_total;

		//	Para volver a rellenar los desplegables, etc
		$proveedores = Proveedor::all();
    	$sociedades = Sociedad::all();
    	$categorias = Categoria::all();
		// $estadosPedidos = EstadoPedido::where('nombre', '<>', 'en_creacion')->get();
		$estadosPedidos = EstadoPedido::whereIn('nombre', ["cursado", "pendiente_recibir", "recibido_parcialmente", "finalizado"])->get();

		$departamentos = $usuario->departamentosConPermiso();
		$campos = $usuario->camposConPermiso();

		return view('informes.resultadolineasporcategorias', compact('usuario', 'resultado', 'total', 'filtros', 'proveedores', 'campos', 'departamentos', 'sociedades', 'categorias', 'estadosPedidos'));
	}
}

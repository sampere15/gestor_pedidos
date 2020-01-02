@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<h2>Crear nuevo pedido</h2>	
		</div>
	</div>

	{{-- Bloque que muestra los errores --}}
	@if($errors->any())
        <div class="alert alert-danger">
            <h6>Por favor corrige los siguientes errores:</h6>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

	<div class="row">
		<div class="col-md-3 col-sm-3 pull-right">
			<h3>Creado por: <small>{{ Auth::user()->nombre . ' ' . Auth::user()->apellidos }}</small></h3>	
		</div>
	</div>
	<div class="row col-md-12 cold-sm-12">
		<form class="form-horizontal" id="formularioNuevoPedido" method="POST" action="">
			{{ csrf_field() }}
			<div class="form-group">

			    <label for="btnBuscarProveedor" class="col-md-1 col-sm-12 control-label">Proveedor:</label>
			    <div class="col-md-2 col-sm-12">
				    <input type="text" class="form-control" name="proveedorNombre" id="btnBuscarProveedor" placeholder="Piche para seleccionar proveedor" value="{{ old('proveedorNombre') }}" readonly required>
			    </div>
				<input type="hidden" id="proveedor_id" name="proveedor_id" value="{{ old('proveedor_id') }}">
				
			    <label for="departamentoSelect" class="col-md-1 col-sm-12 control-label">Departamento:</label>
			    <div class="col-md-2 col-sm-12">
					<select class="form-control" id="departamentoSelect" name="departamentoSelect">
						@foreach ($departamentos as $departamento)
						<option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
				    	@endforeach
				    </select>
				</div>
				
				<label for="campoSelect" class="col-md-1 col-sm-12 control-label">Campo:</label>
				<div class="col-md-2 col-sm-12">
					<select class="form-control" id="campoSelect" name="campoSelect" onchange="ActualizarDatos()">
						@foreach ($campos as $campo)
							<option @if($campo->nombre == $usuario->campo->nombre) selected="true" @endif value="{{ $campo->id }}">{{ $campo->nombre }}</option>
						@endforeach
					</select>
				</div>
				
			    <label for="socieadSelect" class="col-md-1 col-sm-12 control-label">Sociedad:</label>
			    <div class="col-md-2 col-sm-12">
				    <select class="form-control" id="socieadSelect" name="sociedadSelect">
				    	@foreach ($usuario->campo->sociedades as $sociedad)
				    		<option @if($usuario->campo->sociedadFavorita->nombre == $sociedad->nombre) selected="true" @endif value="{{ $sociedad->id }}">{{ $sociedad->nombre }}</option>
				    	@endforeach
				    </select>
				</div>
				
			</div>

			<div class="form-group">

				<label for="direccionSelect" class="col-md-1 col-sm-12 control-label">Dirección:</label>
				<div class="col-md-5 col-sm-10 col-xs-10">
					<select class="form-control" id="direccionSelect" name="direccionSelect" onchange="SelectDireccionCambiado()" 
						value="{{ old('direccionSelect') }}">
						@foreach ($usuario->campo->direcciones->where('activo') as $direccion)
							<option value="{{ $direccion->id }}" {{ old('direccionSelect') == $direccion->id ? "selected" : "" }}>{{ $direccion->nombre }}</option>
						@endforeach
					</select>
				</div>

				<div class="col-sm-1 col-md-1">
					<button type="button" id="btnRevisarDireccion" class="btn btn-default" aria-label="Left Align">
					  	<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
					</button>
				</div>

				<div class="col-md-1 col-sm-12 col-xs-12">
					<button class="btn btn-sm btn-primary" disabled="true">Nueva dirección</button>
				</div>

			</div>

			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<h3>Líneas de pedido</h3>
					<div class="col-md-12 col-sm-12" style="border: 1px solid #ccc; border-radius: 16px;">
						<table id="tablaLineasPedido" class="table table-condensed">
							<thead>
								<tr>
									<th width="10px">Nº</th>
									<th width="15%">Categoría</th>
									<th>Descripción</th>
									<th width="5%">Unidades</th>
									<th width="10%">Formato</th>
									<th width="10%"><span style="color: red; font-size: 80%">Para decimales usar punto "."</span><br>Precio €</th>
									<th width="10%">Total</th>
									<th width="10px"></th>
								</tr>
							</thead>
							<tbody>
								<tr id="lineaPedido1">
									<td class="linea_pedido_id" name="linea_pedido1">1</td>
									<td>
										<select class="form-control" id="selectCategoria1" name="selectCategoria1">
											@foreach ($categorias as $categoria)
												<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
											@endforeach
										</select>
									</td>
									<td><input class="form-control" type="text" name="inputDescripcion1" value="{{ old("inputDescripcion1") }}"></td>
									<td><input class="form-control" onchange="ActualizarTotalLineaPedido(this)" numero_linea="1" type="text" name="inputUnidades1" id="inputUnidades1" value="{{ old('inputUnidades1') }}"></td>
									<td>
										<select class="form-control" id="selectFormato1" name="selectFormato1">
											@foreach ($formatos as $formato)
												<option value="{{ $formato->id }}">{{ $formato->nombre }}</option>
											@endforeach
										</select>
									</td>
									<td><input class="form-control" onchange="ActualizarTotalLineaPedido(this)" numero_linea="1" type="text" name="inputPrecio1" id="inputPrecio1" value="{{ old('inputPrecio1') }}"></td>
									<td><input class="form-control totalLinea" type="readonly" name="inputTotal1" id="inputTotal1" readonly
										value="{{ old('inputTotal1', 0) }}"></td>
									<td>
										<a class="btn btn-sm btn-danger" onclick="BtnEliminarLineaPedido(this)" data-toggle="modal" data-target="#eliminarLineaModal">
											<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<input type="hidden" name="inputTotalPedido" id="inputTotalPedido" value="0">
					<hr style="border-style: inset; border-width: 1px;">
			        <div id="totalPedido" class="row col-md-12 col-sm-12 pull-right" style="margin-top: 10px">
			        	<b>Total Pedido:</b> 0 €
			        </div>
					<div class="row col-md-1 pull-right" style="margin-right: 10px;margin-top: 10px">
						<a onclick="InsertarFilaTabla()" class="btn btn-sm btn-primary">Nueva línea</a>
					</div>
				</div>
			</div>
			<hr style="border-style: inset; border-width: 1px;">
			<h3 style="color: #39bfc9">Estados Especiales</h3>
			<div class="form-group">
				<label>
				    <input type="checkbox" name="cbEstadosEspaciales" onclick="ComprobarEstadosEspeciales(this)"> Marcar un estado especial
				</label>
			</div>
			<div class="form-group">
                <label>
                    <input type="radio" id="rbSolicitadoProveedor" name="rbEstadosEspeciales" value="rbSolicitadoProveedor" disabled> Se ha contactado con el proveedor para que marche el pedido
                </label>
            </div>
            <div class="form-group">
				<label>
                    <input type="radio" id="rbMaterialRecibido" name="rbEstadosEspeciales" value="rbMaterialRecibido" disabled> Ya se ha recibido el material
                </label>
			</div>
			<hr style="border-style: inset; border-width: 1px;">
			<div class="form-group">
			    <label for="observaciones">Observaciones:</label>
			    <textarea class="form-control" id="observaciones" name="observaciones" placeholder="Indique aquí si hay alguna observación" rows="5"></textarea>
		 	</div>
			<div class="row col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<a class="btn btn-lg btn-default" id="btnGuardarYContinuar">Guardar y continuar más tarde</a>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6">
					<a class="btn btn-lg btn-success pull-right" id="btnSolicitarPedido">Solicitar pedido</a>
				</div>
			</div>
			<input type="hidden" id="totalLineasPedidoInput" name="totalLineasPedidoInput" value="1">
		</form>
	</div>

	<div id="div_error_calculo" class="alert alert-danger col-md-12 col-ms-12 col-sx-12" hidden>
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<span class="sr-only">Error:</span>
		Tanto las unidades como el precio deben de ser números
	</div>

	<!-- Modal revisión dirección -->
	<div id="revisarDireccionModal" class="modal fade">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 id="modalTitle" class="modal-title">Dirección de entrega</h4>
	            </div>
	            <div id="modalBody" class="modal-body">
	            	<form class="form-horizontal">
		                <div class="form-group">
		                	<label class="col-md-1 col-sm-12 control-label">Calle:</label>
		                	<div class="col-md-11 col-sm-12">
							    <input type="text" class="form-control" id="dir_calle" value="calle ceiba" readonly>
						    </div>
		                </div>
		                <div class="form-group">
		                	<label class="col-md-1 col-sm-6 control-label">Ciudad:</label>
		                	<div class="col-md-5 col-sm-6">
							    <input type="text" class="form-control" id="dir_ciudad" value="Torre Pacheco" readonly>
						    </div>
						    <label class="col-md-2 col-sm-6 control-label">Cod. Postal:</label>
		                	<div class="col-md-4 col-sm-6">
							    <input type="text" class="form-control" id="dir_cod_postal" value="30700" readonly>
						    </div>
		                </div>
		                <div class="form-group">
		                	<label class="col-md-1 col-sm-6 control-label">Provincia:</label>
		                	<div class="col-md-5 col-sm-6">
							    <input type="text" class="form-control" id="dir_provincia" value="Murcia" readonly>
						    </div>
						    <label class="col-md-2 col-sm-6 control-label">Pais:</label>
		                	<div class="col-md-4 col-sm-6">
							    <input type="text" class="form-control" id="dir_pais" value="España" readonly>
						    </div>
		                </div>
		                <div class="form-group">
		                	<label class="col-md-1 col-sm-6 control-label">Contacto:</label>
		                	<div class="col-md-5 col-sm-6">
							    <input type="text" class="form-control" id="dir_persona_contacto" value="Adrián Sampere Lorente" readonly>
						    </div>
						    <label class="col-md-2 col-sm-6 control-label">Correo:</label>
		                	<div class="col-md-4 col-sm-6">
							    <input type="text" class="form-control" id="dir_correo_contacto" value="sistemas@gnkgolf.com" readonly>
						    </div>
		                </div>
	                </form>
	            </div>
	            <div class="modal-footer">
	                <div class="col-md-6 col-sm-6">
	                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	{{-- Fin del modal revisión dirección --}}

	<!-- Modal selección proveedor -->
	<div id="selectorProveedorModal" class="modal fade">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 id="modalTitle" class="modal-title">Seleccione al proveedor</h4>
	            </div>
	            <div id="modalBody" class="modal-body">
	                <table id="tablaListadoProveedores" class="table table-condensed table-striped" style="width: 100%">
	                    <thead>
	                        <tr>
	                        	<th>Nombre</th>
	                        	<th>Teléfono contacto</th>
	                        	<th>Correo</th>
	                        	<th></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach ($proveedores as $proveedor)
	                    		<tr>
	                    			<td>{{ $proveedor->nombre }}</td>
	                    			<td>{{ $proveedor->telefono_contacto }}</td>
	                    			<td>{{ $proveedor->correo_contacto }}</td>
	                    			<td>
	                    				<button class="btn btn-sm btn-default btnSeleccionarProveedor" data-proveedor_id="{{ $proveedor->id }}" data-proveedor_nombre="{{ $proveedor->nombre }}">Seleccionar</button>
	                    			</td>
	                    		</tr>
	                    	@endforeach
	                    </tbody>
	                </table>
	            </div>
	            <div class="modal-footer">
	                <div class="col-md-6 col-sm-6">
	                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	{{-- Fin del modal selección proveedor --}}

	<!-- Modal eliminar línea -->
	<div id="eliminarLineaModal" class="modal fade modal-en-medio">
	    <div class="modal-dialog modal-sm">
	        <div class="modal-content">
	            <div id="modalBody" class="modal-body">
	            	¿Eliminar esta línea de pedido?
	            </div>
	            <div class="modal-footer">
	                <div class="col-md-12 col-sm-12">
	                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	                    <button type="button" class="btn btn-danger" onclick="ConfirmarEliminarLineaPedido()" data-dismiss="modal">Eliminar</button>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	{{-- Fin del modal eliminar línea --}}

	

@endsection

@push('footscripts')
	{{-- JavaScript para la tabla que nos permite buscar los proveedores --}}
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">
    	//	Aquí vamos a guardar los datos de todas las direcciones
    	var array_direcciones = {!! $usuario->campo->direcciones !!};
    	var campoSeleccionado_id;	//	Guarda el ID del campo que tenemos seleccionado en el select
    	var tablaLineasPedido = document.getElementById("tablaLineasPedido");	//	Referencia a nuestra tabla de líneas del pedido
    	var lineasPedido = 1;	//	variable para contabilizar cuantas líneas de pedidos llevamos. Hará de ID de nuestra línea
    	var categorias = {!! $categorias !!};	//	Listado de las categorías
    	var formatos = {!! $formatos !!};		//	Listado de los formatos
    	var num_errores_calculo;		//	Nos sirve para controlar cuantes errores a la hora de calcular los precios hay para quitar el mensaje o no
    	var rowLineaPedido;				//	Referencia a la linea de pedido sobre la que estamos trabajando
    	var rutaSolicitarPedido = "{!! route('pedidos.guardar') !!}";
    	var rutaGuardarPedido = "{!! route('pedidos.guardartemporal') !!}";

    	// console.log(array_direcciones);

        //  Cuando la página esté cargada
        $(document).ready(function() 
        {
            //  Formateamos la tabla de pedidos
            $('#tablaListadoProveedores').DataTable({
                "paging":   true,
                "ordering": true,
                "info":     true,
                "pageLength": 25,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Ningún registro encontrado",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar: "
                },
                "order": [[ 0, "asc" ]],
                "searching": true,
            });

            //	Una vez esté cargado el documento cargamos los datos de la dirección. Por defecto cargaremos la primera que tenga registrada
            ActualizarDatosModalDireccion(array_direcciones[0]);

            //	Actualizamos el total del pedido por si ha dado un error la página y está la información de la primera linea introducida
            ActualizarTotalPedido();

            // console.log(rutaSolicitarPedido);
            // console.log(rutaGuardarPedido);
        } );

        //	Cuando pulsamos en el input del proveedor mostramos el modal dónde podremos realizar búscaquedas
        $('#btnBuscarProveedor').click(function()
        {
            $('#selectorProveedorModal').modal('show');
        });

        //	Muestra el modal que tiene los datos de la direccion
        $('#btnRevisarDireccion').click(function()
        {
        	$('#revisarDireccionModal').modal('show');
        });

        //	Confirma la selección del proveedor en el modal de búsqueda
        $('.btnSeleccionarProveedor').click(function()
        {
        	var proveedor_id = $(this).attr('data-proveedor_id');
        	// console.log('proveedor seleccionado - ' + proveedor_id);
        	$('#selectorProveedorModal').modal('hide');
        	$('#btnBuscarProveedor').attr('value', $(this).attr('data-proveedor_nombre'));
        	$('#proveedor_id').attr('value', proveedor_id);
        });

        //	Cuando pulsamos sobre el botón de guardar el pedido y seguir más tarde
        $('#btnGuardarYContinuar').click(function()
        {
        	//	Configuramos el action del formulario para que realice una accion u otra
        	$('#formularioNuevoPedido').attr('action', rutaGuardarPedido);
        	$('#formularioNuevoPedido').submit();
        });

        $('#btnSolicitarPedido').click(function()
        {
        	//	Configuramos el action del formulario para que realice una accion u otra
        	$('#formularioNuevoPedido').attr('action', rutaSolicitarPedido);
        	$('#formularioNuevoPedido').submit();
        });

        //	Cuando cambia de campo en el select, tenemos que recuperar los datos de sus sociedades, direcciones y departamentos
        function ActualizarDatos()
        {
        	//	Recuperamos el ID del campo que hemos seleccionado
        	campoSeleccionado_id = document.getElementById("campoSelect").value;
        	//	Actualizamos los datos de la sociedad y sólo mostramos los que podamos usar con el campo seleccionado
        	ActualizarDatosSocidades();

        	//	Actualizamos los departamentos que tenga este campo
        	ActualizarDatosDepartamentos();

        	//	Como hemos cambiado de campo, revisamos si tenemos las direcciones de este campo ya descargadas
        	if(ComprobarSiDireccionCampoDescargadas() == false)
        	// {
        		// console.log('direcciones no descargadas');
        		DescargarDirecciones(campoSeleccionado_id);		//	Descargamos las direcciones del campo indicado
        	// }
        	// else
        		// console.log('direcciones descargadas');
        }

        function ActualizarDatosSocidades()
        {
        	//	Ahora mandamos la petición para obtener cual sería el ID de socidad favorita del campo seleccionado
        	var ruta = '{{ url('/campos') }}' + '/' + campoSeleccionado_id + '/sociedadfavoritaysociedades';
        	$.ajax({
                url: ruta,
                type: "POST",
                dataType: "json",
                success: function(datos, estado)
                {
                	var sociedad_favorita_id = datos["sociedad_favorita_id"];;		//	Guardamos la información de la sociedad favorita para el campo seleccionado
                	var sociedades = datos["sociedades"];

                	//	Llamamos a la función que se encarga de eliminar las sociedades del campo antiguo
                	ActualizarSelectSociedades(sociedad_favorita_id, sociedades);
                },
                error: function(estado, errorThrown)
                {
                    console.log(estado);
                    console.log(errorThrown);
                }
            });
        }

        function ActualizarDatosDepartamentos()
        {
        	//	Ahora mandamos la petición para descargar los departamentos de esta sociedad
        	var ruta = '{{ url('/campos') }}' + '/' + campoSeleccionado_id + '/departamentos';

        	// console.log(ruta);

        	$.ajax({
                url: ruta,
                type: "POST",
                dataType: "json",
                success: function(datos, estado)
                {
                	var departamentos = datos["departamentos"];

                	//	LLamamos a la función que se encarga de actualizar el select de los departamentos de dicho campo
                	ActualizarSelectDepartamentos(departamentos);
                },
                error: function(estado, errorThrown)
                {
                    console.log(estado);
                    console.log(errorThrown);
                }
            });
        }

        //	Una vez que hemos cambiado de campo, nos actualiza con las sociedades disponibles para dicho campo
        function ActualizarSelectSociedades(sociedad_favorita_id, sociedades)
        {
        	//	Ahora que ya tenemos la sociedad favorita del campo, la seleccionamos en el desplegable
        	var socieadSelect =document.getElementById("socieadSelect");
        	socieadSelect.innerHTML = '';

        	var html = "";		//	Aquí guardaremos el resultado final

        	for(var i = 0; i < sociedades.length; i++)
        	{
        		opcion1 = "<option value=" + '"' + sociedades[i]['id'] + '"';
    			opcion2 = " selected=" + '"' + "true" + '"';
        		opcion3 = ">" + sociedades[i]['nombre'] + "</option>";

        		html = html + opcion1;

        		//	Si es la sociedad favorita del campo la dejamos seleccionada
        		if(sociedad_favorita_id == sociedades[i]['id'])
        		{
        			html = html + opcion2;
        		}
        		
        		html = html + opcion3;
        	}

        	//	Finalmente, una vez que hemos reconstruido todas las opciones del select, actualizamos el select con el nuevo html
        	socieadSelect.innerHTML = html;
        }

        //	Actualiza el select de departamentos que tiene dicho campo
        function ActualizarSelectDepartamentos(departamentos)
        {
        	//	Recuperamos el select en el que tenemos lo departamentos y lo actualizamos
        	var departamentoSelect =document.getElementById("departamentoSelect");
        	departamentoSelect.innerHTML = '';

        	var html = "";		//	Aquí guardaremos el resultado final

        	for(var i = 0; i < departamentos.length; i++)
        	{
        		opcion1 = "<option value=" + '"' + departamentos[i]['id'] + '"';
        		opcion2 = ">" + departamentos[i]['nombre'] + "</option>";

        		html = html + opcion1 + opcion2;
        	}

        	//	Finalmente, una vez que hemos reconstruido todas las opciones del select, actualizamos el select con el nuevo html
        	departamentoSelect.innerHTML = html;
        }

        //	Comprueba si las direcciones del campo seleccionado están ya descargadas o no
        function ComprobarSiDireccionCampoDescargadas()
        {
        	var encontrado = false;		//	Variable de control para saber si tenemos que descargar nuevas direcciones o no

        	for(var i = 0; i < array_direcciones.length; i++)
        	{
        		if(array_direcciones[i]['campo_id'] == campoSeleccionado_id)
        		{
        			encontrado = true;
        			i = array_direcciones.length;
        		}
        	}
        	return encontrado;
        }

        //	Descarga las direcciones del campo indicado
        function DescargarDirecciones()
        {
        	//	Ahora mandamos la petición para obtener las direcciones del campo seleccionado
        	var ruta = '{{ url('/campos') }}' + '/' + campoSeleccionado_id + '/direcciones';
        	$.ajax({
                url: ruta,
                type: "POST",
                dataType: "json",
                success: function(datos, estado)
                {
                	//	No vamos a actualizar las direcciones descargadas si no que sólo vamos a dejar las nuevas, para no poder seleccionar una dirección de otro centro
                	array_direcciones = datos["direcciones"];				//	Guardamos las direcciones descargadas
                	ActualizarSelectDirecciones();							//	Actualizamos las opciones del select
                	ActualizarDatosModalDireccion(array_direcciones[0]);	//	Actualizamos los datos que aparecen en el modal
                },
                error: function(estado, errorThrown)
                {
                    console.log(estado);
                    console.log(errorThrown);
                }
            });
        }

        //	Carga las direcciones descargadas en el select para actualizarlo
        function ActualizarSelectDirecciones()
        {
        	//	Ahora que ya tenemos la sociedad favorita del campo, la seleccionamos en el desplegable
        	var direccionSelect =document.getElementById("direccionSelect");
        	direccionSelect.innerHTML = '';

        	for(var i = 0; i < array_direcciones.length; i++)
        	{
        		direccionSelect.innerHTML = direccionSelect.innerHTML + "<option value=" + '"' + array_direcciones[i]["id"] + '"' + ">" + array_direcciones[i]["nombre"] + "</option>";
        	}
        }

        //	Cuando pinchamos en una dirección diferente en el select de direcciones, refrescamos los datos del modal
        function SelectDireccionCambiado()
        {
        	direccionSeleccionada_id = document.getElementById("direccionSelect").value;
        	var direccion;	//	guardamos la direccion que queremos cargar en el modal

        	for(var i = 0; i < array_direcciones.length;i++)
        	{
        		if(array_direcciones[i]["id"] == direccionSeleccionada_id)
        		{
        			direccion = array_direcciones[i];
        			i = array_direcciones.length;
        		}
        	}

        	ActualizarDatosModalDireccion(direccion);
        }

        //	Carga los datos de la dirección
        function ActualizarDatosModalDireccion(direccion)
        {
        	// console.log(direccion);
        	$('#dir_calle').attr('value', direccion['calle']);
        	$('#dir_ciudad').attr('value', direccion['ciudad']);
        	$('#dir_codigo_postal').attr('value', direccion['codigo_postal']);
        	$('#dir_provincia').attr('value', direccion['provincia']);
        	$('#dir_pais').attr('value', direccion['pais']);
        	$('#dir_persona_contacto').attr('value', direccion['persona_contacto']);
        	$('#dir_correo_contacto').attr('value', direccion['correo_contacto']);
        }

        //	Inserta una nueva fila en la tabla de línea de pedidos
        function InsertarFilaTabla()
        {
        	lineasPedido++;		//	Aumentamos el contador de líneas de pedidos
        	document.getElementById("totalLineasPedidoInput").value = lineasPedido;		//	Indicamos cuantas líneas de pedido hemos añadido
        	//	Insertamos una nueva línea
        	var nuevaLinea = tablaLineasPedido.insertRow(tablaLineasPedido.length);
        	nuevaLinea["id"] = "lineaPedido" + lineasPedido;
        	// nuevaLinea.newCell = nuevaLinea.insertCell(0).innerHTML = lineasPedido;

        	//	Añadimos la celda donde pondremos el número de línea
        	var celdaID = nuevaLinea.insertCell(0);
        	celdaID.className = "linea_pedido_id";
        	celdaID.name = "lineaPedido" + lineasPedido;
        	celdaID.innerHTML = lineasPedido;

        	//	Recogemos la plantilla de select de categorías, su clase y actualizamos su id
        	var selectCategoriasPlantilla = $("#selectCategoria1").html();
        	nuevaLinea.insertCell(1).innerHTML = 
        		`<select class="form-control" id="selectCategoria${lineasPedido}" name="selectCategoria${lineasPedido}">` + selectCategoriasPlantilla + '</select>';

        	//	Añadimos la descripción
        	nuevaLinea.insertCell(2).innerHTML = 
        		`<input class="form-control" type="text" name="inputDescripcion${lineasPedido}">`;

        	//	Añadimos las unidades
        	nuevaLinea.insertCell(3).innerHTML = `<td><input id="inputUnidades${lineasPedido}" class="form-control" onchange="ActualizarTotalLineaPedido(this)" type="text" name="inputUnidades${lineasPedido}" numero_linea="${lineasPedido}"></td>`;

        	//	Recogemos la plantilla de select de formatos, su clase y actualizamos su id
        	var selectFormatosPlantilla = $("#selectFormato1").html();
        	nuevaLinea.insertCell(4).innerHTML = `<select id="selectFormato${lineasPedido}" name="selectFormato${lineasPedido}" class="form-control">` + selectFormatosPlantilla + '</select>';

        	//	Añadimos el input para el precio
        	nuevaLinea.insertCell(5).innerHTML = `<input id="inputPrecio${lineasPedido}" class="form-control" type="text" onchange="ActualizarTotalLineaPedido(this)" name="inputPrecio${lineasPedido}" numero_linea="${lineasPedido}">`;

        	//	Añadimos el input para el total
        	nuevaLinea.insertCell(6).innerHTML = 
        		`<input id="inputTotal${lineasPedido}" class="form-control totalLinea" type="readonly" name="inputTotal${lineasPedido}" readonly value="0">`;

        	//	Añadimos el botón para eliminar la línea de pedido
        	nuevaLinea.insertCell(7).innerHTML = 
        		'<a class="btn btn-sm btn-danger btnEliminarLineaPedido" onclick="BtnEliminarLineaPedido(this)") data-toggle="modal" data-target="#eliminarLineaModal"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
        }

        //	Función que actualiza el total de la linea de pedido
        function ActualizarTotalLineaPedido(elemento)
        {
			var numero_linea = $(elemento).attr("numero_linea");
			var unidades = document.getElementById("inputUnidades" + numero_linea).value;

			//	Redondeamos el precio a 2 decimales si ha introducido precio
			if(document.getElementById("inputPrecio" + numero_linea).value != "")
				document.getElementById("inputPrecio" + numero_linea).value = parseFloat(document.getElementById("inputPrecio" + numero_linea).value).toFixed(2);

			var precio = document.getElementById("inputPrecio" + numero_linea).value;

			//	Si las unidades introducidas están bien quitamos la posible clase de error
			if(isNaN(unidades) == false)
			{
				$("#inputUnidades" + numero_linea).removeClass("error-input");
				document.getElementById("inputTotal" + numero_linea).value = "0";
				ActualizarTotalPedido();
			}
			//	Si las unidades introducidas están bien quitamos la posible clase de error
			if(isNaN(precio) == false)
			{
				$("#inputPrecio" + numero_linea).removeClass("error-input");
				document.getElementById("inputTotal" + numero_linea).value = "0";
				ActualizarTotalPedido();
			}

			//	Si ambos inputs son correctos calculamos el resultado
			if(isNaN(unidades) == false && isNaN(precio) == false)
			{	
				var total = parseFloat(unidades * precio).toFixed(2);

				document.getElementById("inputTotal" + numero_linea).value = total;				//	Calculamos el total
				ActualizarTotalPedido();		//	Actualizamos el total del pedido ahora que los datos de la línea
			}
			else
			{
				document.getElementById("div_error_calculo").removeAttribute('hidden');
				if(isNaN(unidades))
				{
					$("#inputUnidades" + numero_linea).addClass("error-input");
				}
				if(isNaN(precio))
				{
					$("#inputPrecio" + numero_linea).addClass("error-input");
				}
			}

			//	Si no tenemos más errores de introducción de números en los inputs ocultamos el panel
			if(document.getElementsByClassName("error-input").length == 0)
			{
				document.getElementById("div_error_calculo").setAttribute('hidden', 'true');	//	Quitamos mensaje de advertencia
			}
        }

        //	Función que actaliza el total del pedido
        function ActualizarTotalPedido()
        {
        	var totalPedido = 0;			//	Variable para calcular el total del pedido
        	var lineas = document.getElementsByClassName("totalLinea");		//	Recuperamos todos los campos de total de pedido
        	
        	for(var i = 0; i < lineas.length; i++)
        	{
        		//	No hace falta comprobar si es numérico, porque en esta celda va a haber un 0 o el total
    			totalPedido = parseFloat(totalPedido) + parseFloat(lineas[i].value);
        	}

        	document.getElementById("totalPedido").innerHTML = `<b>Total Pedido:</b> ${totalPedido} €`;
        	$('#inputTotalPedido').attr('value', totalPedido);
        }

        //	Recoge la referencia al tr sobre el que estamos actuando	
        function BtnEliminarLineaPedido(elemento)
        {
        	rowLineaPedido = $(elemento).parents('tr');
        }

        //	Confirma que se quiere eliminar la línea de pedido que hemos recogido arriba y así procedemos
        function ConfirmarEliminarLineaPedido()
        {
        	// rowLineaPedido.fadeOut();  //  Ocultamos la fila que queremos borrar
        	var elemento = document.getElementById(rowLineaPedido[0]["id"]);
        	var nodoPadre = elemento.parentNode;
        	nodoPadre.removeChild(elemento);

        	RenumerarLineasPedido();		//	Actualizamos las líneas de pedido y el contador para las siguientes
        	ActualizarTotalPedido();		//	Actualizamos el total del pedido ya que hemos quitado línea de pedido
        }

        //	Renombra las líenas de pedido una vez que se ha eliminado una y actualiza el índice de línea de pedido
        function RenumerarLineasPedido()
        {
        	lineasPedido--;		//	Decrementamos el índice de línea de pedido
        	document.getElementById("totalLineasPedidoInput").value = lineasPedido;		//	Indicamos cuantas líneas de pedido hemos añadido
        	var lineasPedidoIds = document.getElementsByClassName("linea_pedido_id");	//	Recuperamos todas las celdas de línea de pedido

        	for(var i = 0; i < lineasPedidoIds.length; i++)
        	{
        		lineasPedidoIds[i].innerHTML = i+1;
        	}
        }

        //	Cuando marcamos o desmarcamos los estados especiales se encarga de desactivar y limpiar los diferentes estados
        function ComprobarEstadosEspeciales(element)
        {
        	// console.log(element.checked)
        	if(element.checked)
        	{
        		$('#rbSolicitadoProveedor').attr('disabled', false);
        		$('#rbMaterialRecibido').attr('disabled', false);
        	}
        	else
        	{
        		$('#rbSolicitadoProveedor').attr('disabled', true);
        		$('#rbMaterialRecibido').attr('disabled', true);
        		document.getElementById('rbSolicitadoProveedor').checked = false;
        		document.getElementById('rbMaterialRecibido').checked = false;
        	}
        }
    </script>
@endpush

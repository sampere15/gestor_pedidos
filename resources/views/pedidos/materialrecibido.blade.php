@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-6 col-md-6">
			<h2>Recepción de material del pedido {{ $pedido->id }}</h2>
            <a href="{{ route("pedidos.verdetalles", $pedido) }}">
                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Atrás</button>
            </a>
		</div>
		<div class="col-sm-6 col-md-6" style="text-align: right;">
			<h2>Estado del pedido: <span style="color: {{ $pedido->estadoPedido->color }}">{{ $pedido->estadoPedido->nombre_mostrar }}</span></h2>
            <div class="pull-right">
                <a class="btn btn-default" href="{{ route('pedidos.verhistorico', $pedido) }}">
                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Histórico
                </a>
            </div>
		</div>
	</div>

	<div class="row col-md-12 col-sm-12">
        <div class="row col-md-12 col-sm-12">
        	<div class="col-md-6 col-sm-6">
        		<h3>Pedido para campo: <small>{{ $pedido->campo->nombre }}</small></h3>
        	</div>
        	<div class="col-md-6 col-sm-6">
        		<h3>Solictida el pedido: <small>{{ $pedido->usuarioRealizaPedido->nombre }}</small></h3>
        	</div>
        </div>
        <div class="row col-md-12 col-sm-12">
        	<div class="col-md-6 col-sm-6">
        		<h3>Proveedor: <small>{{ $pedido->proveedor->nombre }}</small></h3>
        	</div>
        	<div class="col-md-6 col-sm-6">
        		<h3>Fecha creación: <small>{{ $pedido->created_at }}</small></h3>
        	</div>
        </div>
    </div>

    <div class="row">
    	<button class="pull-right btn btn-lg btn-primary" onclick="MarcarTodoComoRecibido()">Marcar todo como recibido</button>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12 table-responsive">
        	<form method="POST" id="formactualizarmaterialpedido" action="{{ route('pedidos.actualizarmaterialrecibido', $pedido) }}">
        		@csrf
        		@method('PUT')

	            <table id="tablaListadoLineasPedido" class="table table-condensed table-striped" style="width: 100%">
	                <thead>
	                    <tr>
	                    	<th>Línea</th>
	                    	<th>Categoría</th>
	                    	<th>Descripción</th>
	                    	<th>Unidades</th>
	                		<th>Formato</th>
							<th>Estado línea</th>
							<th width="50px"></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    @foreach($pedido->lineasPedido as $linea)
	                        <tr>
								<input type="hidden" id="categoriaLineaId{{ $linea->numero_linea }}" name="categoriaLineaId{{ $linea->numero_linea }}" value="{{ $linea->categoria_id }}">
								<input type="hidden" id="descripcionLinea{{ $linea->numero_linea }}" name="descripcionLinea{{ $linea->numero_linea }}" value="{{ $linea->descripcion }}">
								<input type="hidden" id="unidadesLinea{{ $linea->numero_linea }}" name="unidadesLinea{{ $linea->numero_linea }}" value="{{ $linea->unidades }}">
								<input type="hidden" id="formatoLineaId{{ $linea->numero_linea }}" name="formatoLineaId{{ $linea->numero_linea }}" value="{{ $linea->formato_id }}">
								<input type="hidden" id="precioLinea{{ $linea->numero_linea }}" name="precioLinea{{ $linea->numero_linea }}" value="{{ $linea->precio }}">

	                        	<td>{{ $linea->numero_linea }}</td>
	                        	<td id="categoriaLinea{{ $linea->numero_linea }}">{{ $linea->Categoria->nombre }}</td>
	                        	<td>{{ $linea->descripcion }}</td>
	                        	<td id="unidadesLineaTabla{{ $linea->numero_linea }}">{{ $linea->unidades }}</td>
	                        	<td id="formatoLinea{{ $linea->numero_linea }}">{{ $linea->Formato->nombre }}</td>
	                        	<td>
	                        		<select class="form-control selectEstadoPedido" name="selectEstadoLinea{{ $linea->numero_linea }}" id="selectEstadoLinea{{ $linea->numero_linea }}">
	                        			@foreach ($estadosLineas as $estadoLinea)
	                        				<option value="{{ $estadoLinea->id }}" {{ $linea->estado_linea_id == $estadoLinea->id ? "selected" : "" }}>{{ $estadoLinea->nombre }}</option>
	                        			@endforeach
	                        		</select>
								</td>
								<td><a href="#" data-toggle="modal" data-target="#modalLineaParcialmenteRecibida" data-numerolinea="{{ $linea->numero_linea }}" onclick="ObtenerNumeroLinea(this)" class="btn btn-sm btn-info">Recibido parcial</a></td>
	                        </tr>
	                    @endforeach
	                </tbody>
				</table>
				
			<input type="hidden" id="numeroLineas" name="numeroLineas" value="{{ count($pedido->lineasPedido) }}">

	            <div class="row col-md-12 col-sm-12 col-xs-12">
	            	<div class="col-md-12 col-sm-12 col-xs-12">
						<a href="#" class="btn btn-lg btn-warning" onclick="javascript:location.reload(true)">Deshacer cambios</a>
	            		<button type="submit" class="bnt btn-lg btn-success pull-right">Actualizar estado recepción</button>	
	            	</div>
	            </div>
        	</form>
        </div>
	</div>
	
	<!-- Modal línea recibida parcialmente -->
	<div id="modalLineaParcialmenteRecibida" class="modal fade">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 id="modalTitle" class="modal-title">¿Qué cantidad ha recibido de la línea seleccionada?<span id="titulo_unidades" style="color: red"></span></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
	                <table id="tablaListadoLineasPedido" class="table table-condensed table-striped" style="width: 100%">
						<thead>
							<tr>
								<th>Línea</th>
								<th>Categoría</th>
								<th>Descripción</th>
								<th>Unidades</th>
								<th>Formato</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="modal_lineaPedido"></td>
								<td id="modal_categoriaNombre"></td>
								<td id="modal_descripcion"></td>
								<td><input type="text" id="modal_unidades" onkeydown="if (event.keyCode == 13) ComprobarCantidadIntroducida();" onkeyup="CheckDecimales(this)"></td>
								<td id="modal_formato"></td>
							</tr>
						</tbody>
					</table>

					<div class="row" id="aviso_error_modal" style="text-align: center; color: red" hidden>
						La cantidad introducida no es válida. Probablemente haya introducido más de lo posible o menor a uno
					</div>
	            </div>
	            <div class="modal-footer">
	                <div class="pull-rigth">
	                    <button type="button" class="btn btn-default" onclick="OcultarMensajeError()" data-dismiss="modal">Cancelar</button>
	                    <button type="button" class="btn btn-success" onclick="ComprobarCantidadIntroducida()">Actualizar línea</button>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	{{-- Fin del modal línea recibida parcialmente --}}
@endsection

@push('footscripts')
	<script type="text/javascript">

		//	Almacenamos las líneas de pedidos "originales", por si tras marcar modificar una linea como recibida parcialmente se quiere restablecer los datos
		// var lineasPedido = {!! $pedido->lineasPedido !!};
		// var lineaSeleccionada;	//	Aquí guardaremos la información de la línea que queremos marcar como recibida parcialmente
		// var unidadesElemento;	//	Elemento HTML en el que están las unidades de la línea que más tarde actualizaremos con las unidades recibidas
		var numeroLineaSeleccionada = 0;
		var cantidadLineasPedido = {!! count($pedido->lineasPedido) !!};
		
		//	Marca todas las líneas como recibidas
		function MarcarTodoComoRecibido()
		{
			var selectsEstadoLinea = document.getElementsByClassName("selectEstadoPedido");
			// console.log(selectsEstadoLinea);
			for(let item of selectsEstadoLinea)
			{
				var value = 1;
				for(let opcion of item.options)
				{
					// console.log(opcion.text + " - " + opcion.value);
					if(opcion.text == "recibida")
					{
						value = opcion.value;
						break;
					}
				}
				item.value = value;
			}
		}

		//	Recupera el id de la línea en la que hemos pulsado
		function ObtenerNumeroLinea(elemento)
		{
			numeroLineaSeleccionada = elemento["dataset"].numerolinea;			//	Recupermaos el número de línea que vamos a editar

			CargarDatosLineaEnModal();											//	Cargamos esos datos en el modal
		}

		//	Carga los datos de la línea seleccionada en el modal para su modificación
		function CargarDatosLineaEnModal()
		{
			document.getElementById("modal_lineaPedido").innerHTML = numeroLineaSeleccionada;
			document.getElementById("modal_categoriaNombre").innerHTML = document.getElementById("categoriaLinea" + numeroLineaSeleccionada).innerHTML;
			document.getElementById("modal_descripcion").innerHTML = document.getElementById("descripcionLinea" + numeroLineaSeleccionada).value;
			document.getElementById("titulo_unidades").innerHTML = " Actual: " + document.getElementById("unidadesLineaTabla" + numeroLineaSeleccionada).innerHTML;
			document.getElementById("modal_unidades").value = document.getElementById("unidadesLinea" + numeroLineaSeleccionada).value;
			document.getElementById("modal_formato").innerHTML = document.getElementById("formatoLinea" + numeroLineaSeleccionada).innerHTML;
		}

		//	Comprueba que la cantidad recibida introducida sea coherente. Que es mayor que 0 y menor o igual que la cantidad máxima
		function ComprobarCantidadIntroducida()
		{
			//	Recuperamos las unidades introducidas en el modal
			var unidadesRecibidas = parseFloat(document.getElementById("modal_unidades").value).toFixed(2);
			var unidadesTotales = parseFloat(document.getElementById("unidadesLinea" + numeroLineaSeleccionada).value).toFixed(2);

			if(unidadesRecibidas <= 0)
			{
				$('#aviso_error_modal').removeAttr("hidden");
			}
			else
			{
				$('#modalLineaParcialmenteRecibida').modal('hide');
				document.getElementById("aviso_error_modal").hidden = true;

				//	Actualizamos la línea editada
				document.getElementById("unidadesLineaTabla" + numeroLineaSeleccionada).innerHTML = unidadesRecibidas;
				document.getElementById("unidadesLinea" + numeroLineaSeleccionada).value = unidadesRecibidas;

				//	Marar esa línea como recibida
				MarcarLineaComoRecibida();

				//	Creamos una nueva línea
				InsertarNuevaLinea(parseFloat(unidadesTotales - unidadesRecibidas).toFixed(2));
			}
		}

		function OcultarMensajeError()
		{
			document.getElementById("aviso_error_modal").hidden = true;
		}

		//	Actualiza el valor del estado de la linea recibida
		function MarcarLineaComoRecibida()
		{
			// console.log(numeroLineaSeleccionada);
			var select = document.getElementById("selectEstadoLinea" + numeroLineaSeleccionada);
			// console.log(select.selectedIndex);
			// select.selectedIndex = 2;
			// console.log("Valor de select.selectedIndex: " + select.selectedIndex);
			// console.log(select.options);
			
			for(var i = 0; i < select.options.length; i++)
			{
				// console.log("Index " + i + " -> " + select.options[i].innerHTML);
				if(select.options[i].innerHTML == "recibida")
				{
					// console.log("Cumple -> índice: " + i + " , valor " + select.options[i].value);
					// console.log(select.options[i].selected);
					// select.options[i].selected = true;
					// console.log(select.options[i].selected);
					// console.log(select.options[i]);
					select.selectedIndex = i;
					// select.options[i].selected = true;
					break;
				}
			}
		}

		//	Hace una copia de la línea original y devuelve la copia
		function InsertarNuevaLinea(nuevasUnidades)
		{
			//	Recuperamos la tabla de las líneas de pedido e insertamos una nueva línea
			var tablaLineasPedido = document.getElementById("tablaListadoLineasPedido");

			cantidadLineasPedido++;

			//	Actualizamos la información de la cantidad de lineas de pedidos que tenemos
			document.getElementById("numeroLineas").value = cantidadLineasPedido;

			//	Insertamos la nueva línea con los datos de la que hemos editado actualizando el número de unidades
			var nuevaLinea = tablaLineasPedido.insertRow(cantidadLineasPedido);
			nuevaLinea.insertCell(0).innerHTML = cantidadLineasPedido;

			var categoria = nuevaLinea.insertCell(1);
			categoria["id"] = "categoriaLinea" + cantidadLineasPedido;
			categoria.innerHTML = document.getElementById("categoriaLinea" + numeroLineaSeleccionada).innerHTML;
			
			nuevaLinea.insertCell(2).innerHTML = document.getElementById("descripcionLinea" + numeroLineaSeleccionada).value;

			var unidades = nuevaLinea.insertCell(3);
			unidades["id"] = "unidadesLineaTabla" + cantidadLineasPedido;
			unidades.innerHTML = nuevasUnidades;

			var formato = nuevaLinea.insertCell(4);
			formato["id"] = "formatoLinea" + cantidadLineasPedido;
			formato.innerHTML = document.getElementById("formatoLinea" + numeroLineaSeleccionada).innerHTML;

			nuevaLinea.insertCell(5).innerHTML = `<select class="form-control selectEstadoPedido" id="selectEstadoLinea${cantidadLineasPedido}" name="selectEstadoLinea${cantidadLineasPedido}">@foreach ($estadosLineas as $estadoLinea) <option value="{{ $estadoLinea->id }}" {{ $estadoLinea->id == 1 ? "selected" : "" }}>{{ $estadoLinea->nombre }}</option> @endforeach </select>`;
			nuevaLinea.insertCell(6).innerHTML = `<td><a href="#" data-toggle="modal" data-target="#modalLineaParcialmenteRecibida" data-numerolinea="${cantidadLineasPedido}" onclick="ObtenerNumeroLinea(this)" class="btn btn-sm btn-info">Recibido parcial</a></td>`;

			// //	Insertamos un input hidden con el nuevo número de unidades
			var formulario = document.getElementById("formactualizarmaterialpedido");

			// tablaLineasPedido.innerHTML += `<input type="hidden" id="categoriaLineaId${cantidadLineasPedido}" name="categoriaLineaId${cantidadLineasPedido}" value="${document.getElementById("categoriaLineaId" + numeroLineaSeleccionada).value}">`;
			var hiddenCategoria = document.createElement("input");
			hiddenCategoria["id"] = "categoriaLineaId" + cantidadLineasPedido;
			hiddenCategoria["name"] = "categoriaLineaId" + cantidadLineasPedido;
			hiddenCategoria["type"] = "hidden";
			hiddenCategoria["value"] = document.getElementById("categoriaLineaId" + numeroLineaSeleccionada).value;
			formulario.appendChild(hiddenCategoria);

			// tablaLineasPedido.innerHTML += `<input type="hidden" id="descripcionLinea${cantidadLineasPedido}" name="descripcionLinea${cantidadLineasPedido}" value="${document.getElementById("descripcionLinea" + numeroLineaSeleccionada).value}">`;
			var hiddenDescripcion = document.createElement("input");
			hiddenDescripcion["id"] = "descripcionLinea" + cantidadLineasPedido;
			hiddenDescripcion["name"] = "descripcionLinea" + cantidadLineasPedido;
			hiddenDescripcion["type"] = "hidden";
			hiddenDescripcion["value"] = document.getElementById("descripcionLinea" + numeroLineaSeleccionada).value;
			formulario.appendChild(hiddenDescripcion);
			
			// tablaLineasPedido.innerHTML += `<input type="hidden" id="unidadesLinea${cantidadLineasPedido}" name="unidadesLinea${cantidadLineasPedido}" value="${nuevasUnidades}">`;
			var hiddenUnidades = document.createElement("input");
			hiddenUnidades["id"] = "unidadesLinea" + cantidadLineasPedido;
			hiddenUnidades["name"] = "unidadesLinea" + cantidadLineasPedido;
			hiddenUnidades["type"] = "hidden";
			hiddenUnidades["value"] = nuevasUnidades;
			formulario.appendChild(hiddenUnidades);
			
			// tablaLineasPedido.innerHTML += `<input type="hidden" id="formatoLineaId${cantidadLineasPedido}" name="formatoLineaId${cantidadLineasPedido}" value="${document.getElementById("formatoLineaId" + numeroLineaSeleccionada).value}">`;
			var hiddenFormato = document.createElement("input");
			hiddenFormato["id"] = "formatoLineaId" + cantidadLineasPedido;
			hiddenFormato["name"] = "formatoLineaId" + cantidadLineasPedido;
			hiddenFormato["type"] = "hidden";
			hiddenFormato["value"] = document.getElementById("formatoLineaId" + numeroLineaSeleccionada).value;
			formulario.appendChild(hiddenFormato);
			
			// tablaLineasPedido.innerHTML += `<input type="hidden" id="precioLinea${cantidadLineasPedido}" name="precioLinea${cantidadLineasPedido}" value="${document.getElementById("precioLinea" + numeroLineaSeleccionada).value}">`;
			var hiddenPrecio = document.createElement("input");
			hiddenPrecio["id"] = "precioLinea" + cantidadLineasPedido;
			hiddenPrecio["name"] = "precioLinea" + cantidadLineasPedido;
			hiddenPrecio["type"] = "hidden";
			hiddenPrecio["value"] = document.getElementById("precioLinea" + numeroLineaSeleccionada).value;
			formulario.appendChild(hiddenPrecio);
		}

		// Para el input del precio vamos a sustituir la coma utilizada para separar los decimales por el punto
		function CheckDecimales(elemento)
		{
			elemento.value = elemento.value.replace(',', '.');
		}
		
	</script>
@endpush
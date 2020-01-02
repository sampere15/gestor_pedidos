<h2 id="titulo_informe"></h2>

<h3>Filtros disponibles</h3>

<div class="row col-md-12 cold-sm-12">
	<form class="form-horizontal" method="POST" action="#" id="formulario">
		@csrf
		
		<div class="form-group">
			{{-- fecha inicio --}}
			<div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px">
				<label for="dateFechaInicio" class="col-md-4 col-sm-4 control-label">F. inicio:</label>
				<div class="col-md-6 col-sm-6">
					<input type="date" name="dateFechaInicio" id="dateFechaInicio" value="{{ isset($filtros['fInicio']) ? $filtros['fInicio'] : "" }}">
				</div>
				<div class="col-md-2 col-sm-2">
					<button type="button" class="btn btn-default btn-sm" onclick="clearInput('dateFechaInicio')">
						<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
					</button>
				</div>
			</div>
			{{-- fin fecha inicio --}}
			{{-- fecha fin --}}
			<div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px">
				<label for="dateFechaFin" class="col-md-4 col-sm-4 control-label">F. fin:</label>
				<div class="col-md-6 col-sm-6">
					<input type="date" name="dateFechaFin" id="dateFechaFin" value="{{ isset($filtros['fFin']) ? $filtros['fFin'] : "" }}">
				</div>
				<div class="col-md-2 col-sm-2">
					<button type="button" class="btn btn-default btn-sm" onclick="clearInput('dateFechaFin')">
						<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
					</button>
				</div>
			</div>
			{{-- fin fecha fin --}}

			{{-- Proveedor --}}
			@if(isset($proveedores))
				<div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px">
					<label for="btnBuscarProveedor" class="col-md-4 col-sm-4 control-label">Proveedor:</label>
					<div class="col-md-6 col-sm-6">
						<input type="text" class="form-control" name="proveedorNombre" id="btnBuscarProveedor" placeholder="Piche para seleccionar proveedor" value="{{ isset($filtros['proveedor_nombre']) ? $filtros['proveedor_nombre'] : "" }}" readonly>
					</div>
					<div class="col-md-2 col-sm-2">
						<button type="button" class="btn btn-default btn-sm" onclick="clearInput('btnBuscarProveedor')">
							<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
						</button>
					</div>
					<input type="hidden" id="proveedor_id" name="proveedor_id" value="{{ isset($filtros['proveedor_id']) ? $filtros['proveedor_id'] : "" }}">
				</div>
			@endif
			{{-- fin proveedor --}}

			{{-- Es un "chivato" que le indicará al javascript si tiene que hacer algo en especial en cuanto a los selects filtro --}}
			@if(isset($filtros) && !($filtros['departamento_id'] == 0 && $filtros['campo_id'] == 0))
				<div id="hay_filtros"></div>
			@endif
			
			{{-- Departamento --}}
			@if(isset($departamentos))
				<div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px">
					<label for="departamentoSelect" class="col-md-4 col-sm-4 control-label">Departamento:</label>
					<div class="col-md-6 col-sm-6">
						<select class="form-control" id="departamentoSelect" name="departamentoSelect" onchange="DepartamentoSeleccionado()">
							<option value="0" selected>Sin filtro</option>

							@foreach ($departamentos as $departamento)
								<option value="{{ $departamento->id }}" {{ isset($filtros['departamento_id']) && $filtros['departamento_id'] == $departamento->id ? "selected" : "" }}>{{ $departamento->nombre }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2 col-sm-2">
						{{-- <button type="button" class="btn btn-default btn-sm" onclick="resetSelect('departamentoSelect')"> --}}
						<button type="button" class="btn btn-default btn-sm" onclick="ResetSelectDepartamentos()">
							<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
						</button>
					</div>
				</div>
			@endif
			{{-- fin departamento --}}
			
			{{-- Campo --}}
			@if(isset($campos))
				<div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px" id="div_campo">
					<label for="campoSelect" class="col-md-4 col-sm-4 control-label">Campo:</label>
					<div class="col-md-6 col-sm-6">
						<select class="form-control" id="campoSelect" name="campoSelect" onchange="CampoSeleccionado()">
							<option value="0" selected>Sin filtro</option>
							@foreach ($campos as $campo)
									<option value="{{ $campo->id }}" {{ isset($filtros['campo_id']) && $filtros['campo_id'] == $campo->id ? "selected" : "" }}>{{ $campo->nombre }}</option>
								@endforeach
						</select>
					</div>
					<div class="col-md-2 col-sm-2">
						{{-- <button type="button" class="btn btn-default btn-sm" onclick="resetSelect('campoSelect')"> --}}
						<button type="button" class="btn btn-default btn-sm" onclick="ResetSelectCampos()">
							<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
						</button>
					</div>
				</div>
			@endif
			{{-- Fin campo --}}

			{{-- categoria --}}
			@if(isset($categorias))
				<div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px">
					<label for="categoriaSelect" class="col-md-4 col-sm-4 control-label">Categoría:</label>
					<div class="col-md-6 col-sm-6">
						<select class="form-control" id="categoriaSelect" name="categoriaSelect">
							<option value="0" selected>Sin filtro</option>
							@foreach ($categorias as $categoria)
								<option value="{{ $categoria->id }}" {{ isset($filtros['categoria_id']) && $filtros['categoria_id'] == $categoria->id ? "selected" : "" }}>{{ $categoria->nombre }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2 col-sm-2">
						<button type="button" class="btn btn-default btn-sm" onclick="resetSelect('categoriaSelect')">
							<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
						</button>
					</div>
				</div>
			@endif
			{{-- fin categoria --}}

			{{-- sociedad --}}
			@if(isset($sociedades))
				<div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px">
					<label for="socieadSelect" class="col-md-4 col-sm-4 control-label">Sociedad:</label>
					<div class="col-md-6 col-sm-6">
						<select class="form-control" id="socieadSelect" name="sociedadSelect">
							<option value="0" selected>Sin filtro</option>
							@foreach ($sociedades as $sociedad)
								<option value="{{ $sociedad->id }}" {{ isset($filtros['sociedad_id']) && $filtros['sociedad_id'] == $sociedad->id ? "selected" : "" }}>{{ $sociedad->nombre }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2 col-sm-2">
						<button type="button" class="btn btn-default btn-sm" onclick="resetSelect('socieadSelect')">
							<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
						</button>
					</div>
				</div>
			@endif
			{{-- fin sociedad --}}

			{{-- usuario realiza el pedido --}}
			{{-- <div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px">
				<label for="usuarioSelect" class="col-md-4 col-sm-4 control-label">Usuario:</label>
				<div class="col-md-6 col-sm-6">
					<select class="form-control" id="usuarioSelect" name="usuarioSelect">
						<option value="0" selected>Sin filtro</option>
						@foreach ($usuarios as $usuario)
							<option value="{{ $usuario->id }}" {{ isset($filtros['usuario_id']) && $filtros['usuario_id'] == $usuario->id ? "selected" : "" }}>{{ $usuario->nombre }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2 col-sm-2">
					<button type="button" class="btn btn-default btn-sm" onclick="resetSelect('usuarioSelect')">
						<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
					</button>
				</div>
			</div> --}}
			{{-- fin usuario realiza el pedido --}}

			{{-- estado del pedido --}}
			@if(isset($estadosPedidos))
				<div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px" id="div_estado_pedido">
					<label for="estadoPedidoSelect" class="col-md-4 col-sm-4 control-label">Estado pedido:</label>
					<div class="col-md-6 col-sm-6">
						<select class="form-control" id="estadoPedidoSelect" name="estadoPedidoSelect[]" multiple>
							@if(isset($filtros["estados_pedidos"]) && count($filtros["estados_pedidos"]) > 0 && $filtros["estados_pedidos"][0] != "0")
								<option value="0">Sin filtro</option>
								@foreach ($estadosPedidos as $estadoPedido)
									<option value="{{ $estadoPedido->id }}" {{ in_array($estadoPedido->id, $filtros["estados_pedidos"]) ? "selected=selected" : "" }}>{{ $estadoPedido->nombre_mostrar }}</option>
								@endforeach
							@else
								<option value="0" selected="selected">Sin filtro</option>
								@foreach ($estadosPedidos as $estadoPedido)
									<option value="{{ $estadoPedido->id }}">{{ $estadoPedido->nombre_mostrar }}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="col-md-2 col-sm-2">
						<button type="button" class="btn btn-default btn-sm" onclick="resetSelect('estadoPedidoSelect')">
							<span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:red"></span>
						</button>
					</div>
				</div>
			@endif
			{{-- fin del estado del pedido --}}

		</div>

		<div class="row">
			<div class="pull-right">
				<a class="btn btn-info btn-lg" onclick="ResetFormulario()">
					Borrar filtros
				</a>
				<button type="submit" class="btn btn-success btn-lg">
					Enviar consulta
				</button>
			</div>
		</div>
	</form>
</div>

<!-- Modal selección proveedor -->
@if(isset($proveedores))
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
@endif
{{-- Fin del modal selección proveedor --}}
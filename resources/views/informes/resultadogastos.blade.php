@extends('layouts.app')

@push('csstopafter')
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-html5-1.6.1/datatables.min.css"/>
@endpush

@section('content')

	{{-- Incluimos el HTML relativo a los filtros disponibles para el informe --}}
	@include('informes.partials.cabecerafiltros')

	<hr>

	<h2>Resultado Informe</h2>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<table id="tablaResultado" class="table table-condensed table-striped"  style="width: 100%">
	            <thead>
	                <tr>
	                    <th>Nº Pedido</th>
	                    <th>Fecha pedido</th>
	                    <th>Categoría</th>
	                    <th>Campo</th>
	                    <th>Proveedor</th>
	                    <th>Departamento</th>
	                    <th>Sociedad</th>
	                    <th>Estado Pedido</th>
	                    <th>Unidades</th>
	                    <th>Precio</th>
	                    <th width="10%">Total</th>
	                </tr>
	            </thead>
	            <tbody>
	            	@foreach ($resultado as $linea)
	            		<tr>
	            			<td><a href="{{ route('pedidos.verdetalles', $linea->pedido_id) }}">{{ $linea->pedido_id }}</a></td>
	            			<td>{{ $linea->fecha_pedido }}</td>
		                    <td>{{ $linea->categoria_nombre }}</td>
	            			<td>{{ $linea->campo_nombre }}</td>
	            			<td>{{ $linea->proveedor_nombre }}</td>
	            			<td>{{ $linea->departamento_nombre }}</td>
	            			<td>{{ $linea->sociedad_nombre }}</td>
	            			<td>
								{{ $linea->estadoPedido_nombre }}
								@if($linea->estadoPedido_nombre == "Recibido parcialmente")
									({{ $linea->lineaEstado }})
								@endif
							</td>
	            			<td>{{ $linea->linea_unidades }}</td>
	            			<td>{{ $linea->linea_precio }} €</td>
							<td align="right"><b>{{ number_format($linea->linea_unidades * $linea->linea_precio, 2, ",", ".") }} €</b></td>
	            		</tr>
	            	@endforeach
	            </tbody>
	        </table>    
		</div>
	</div>

	<div class="row">
		<div id="totalPedido" class="col-md-11 col-sm-11 col-xs-11" style="margin-top: 10px">
        	<h3 class="pull-right"><b>Total: {{ isset($total) && $total != null ? $total : "0" }} €</b></h3>
        </div>
	</div>

@endsection

@push('footscripts')

	{{-- Incluimos el Javascript relativo a los filtros disponibles para el informe --}}
	@include('informes.partials.scriptfiltros')

	<script type="text/javascript">

		$(document).ready(function() 
		{
			// configuramos el action del formulario dependiendo de la página en la que nos encontremos
			document.getElementById("formulario").setAttribute('action', '{!! route('informes.resultadogastos') !!}');
			document.getElementById("titulo_informe").innerHTML = "Informe de gastos";

			//  Formateamos la tabla de pedidos
			$('#tablaResultado').DataTable({
				"paging":   false,
				"ordering": true,
				"info":     false,
				"searching": false,
				"language": {
					"lengthMenu": "Mostrar _MENU_ registros por página",
					"zeroRecords": "Ningún registro encontrado",
					"info": "Mostrando página _PAGE_ de _PAGES_",
					"infoEmpty": "No records available",
					"infoFiltered": "(filtrado de _MAX_ registros totales)",
					"search": "Buscar: "
				},
				"order": [[ 0, "asc" ]],
				dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'pdfHtml5'
                ]
			});
		});
	
	</script>

@endpush
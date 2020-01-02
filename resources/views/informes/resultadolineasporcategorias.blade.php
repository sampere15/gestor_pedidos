@extends('layouts.app')

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
	                    <th>Categoría</th>
	                    <th>Campo</th>
	                    <th>Proveedor</th>
	                    <th>Total</th>
	                </tr>
	            </thead>
	            <tbody>
	            	@foreach ($resultado as $linea)
	            		<tr>
	            			<td><a href="{{ route('pedidos.verdetalles', $linea->pedido_id) }}">{{ $linea->pedido_id }}</a></td>
		                    <td>{{ $linea->categoria_nombre }}</td>
	            			<td>{{ $linea->campo_nombre }}</td>
	            			<td>{{ $linea->proveedor_nombre }}</td>
	            			<td>{{ number_format($linea->importe, 2, ",", ".") }} €</td>
	            		</tr>
	            	@endforeach
	            </tbody>
	        </table>    
		</div>
	</div>

	<div class="row">
		<div id="totalPedido" class="col-md-6 col-sm-6 col-xs-6 pull-right" style="margin-top: 10px">
        	<h3 class="pull-right"><b>Total: {{ isset($total) && $total != null ? number_format($total, 2, ",", ".") : "0" }} €</b></h3>
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
			document.getElementById("formulario").setAttribute('action', '{!! route('informes.resultadolineascategorias') !!}');
			document.getElementById("titulo_informe").innerHTML = "Informe líneas agrupadas por categorías";

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
			});
		});
	
	</script>

@endpush
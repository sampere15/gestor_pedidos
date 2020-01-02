@extends('layouts.app')

@section('content')

	{{-- Incluimos el HTML relativo a los filtros disponibles para el informe --}}
	@include('informes.partials.cabecerafiltros')

	<hr>

	<h2>Resultado Informe</h2>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<table class="table table-condensed table-striped"  style="width: 50%">
	            <thead>
	                <tr>
	                    <th>Categoría</th>
	                    <th>Total Importe</th>
	                </tr>
	            </thead>
	            <tbody>
	            	@foreach ($resultado as $categoria)
	            		<tr>
	            			<td>{{ $categoria->categoria }}</td>
	            			<td>{{ number_format($categoria->total_categoria, 2, ",", ".") }} €</td>
	            		</tr>
	            	@endforeach
	            </tbody>
	        </table>    
		</div>
	</div>

	<div class="row">
		<div id="totalPedido" class="col-md-6 col-sm-6 col-xs-6" style="margin-top: 10px">
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
			document.getElementById("formulario").setAttribute('action', '{!! route('informes.resultadoporcategorias') !!}');
			document.getElementById("titulo_informe").innerHTML = "Informe de gastos por categoria";
		});
	
	</script>

@endpush
@extends('layouts.app')

@section('content')

	{{-- Incluimos el HTML relativo a los filtros disponibles para el informe --}}
	@include('informes.partials.cabecerafiltros')

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
		});
	
	</script>

@endpush
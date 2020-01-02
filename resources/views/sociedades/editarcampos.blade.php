@extends('layouts.app')

@section('content')
	<h2>Campos que gestiona la sociedad {{ $sociedad->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('sociedades.actualizarcampos', $sociedad) }}">
			@csrf
			@method('PUT')
			
			<div class="col-md-3">
				@foreach ($campos as $campo)
					<div class="row">
	                    <label>
	                        <input type="checkbox" name="campos[]" value="{{ $campo->id }}" {{ $sociedad->GestionaCampo($campo->nombre) ? "checked" : "" }}> {{ $campo->nombre }}
	                    </label>
	                </div>
				@endforeach		

				<button type="submit" id="btnActualizarCampos" class="btn btn-lg btn-success pull-right">Actualizar campos</button>
			</div>
		</form>
	</div>
	
@endsection
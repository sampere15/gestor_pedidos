@extends('layouts.app')

@section('content')
	<h2>Sociedades que gestiona el campo {{ $campo->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('campos.actualizarsociedades', $campo) }}">
			@csrf
			@method('PUT')
			
			<div class="row">
				@foreach ($sociedades as $sociedad)
					<div class="col-md-12 col-sm-12 col-xs-12">
	                    <label>
	                        <input type="checkbox" name="sociedades[]" value="{{ $sociedad->id }}" {{ $sociedad->GestionaCampo($campo->nombre) ? "checked" : "" }}> {{ $sociedad->nombre }}
	                    </label>
	                </div>
				@endforeach		

			</div>

			@if($errors->has('sociedades'))
		        <div class="alert alert-danger">
		            <ul>
		                <strong>{{ $errors->first('sociedades') }}</strong>
		            </ul>
		        </div>
		    @endif

		    <div class="row">
		    	<div class="col-md-3">
					<button type="submit" id="btnActualizarSociedades" class="btn btn-lg btn-success pull-right">Actualizar socidades</button>
		    	</div>
		    </div>
		</form>
	</div
	
@endsection
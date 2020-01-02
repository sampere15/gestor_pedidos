@extends('layouts.app')

@section('content')
	<h2>Campos que tiene el departamento {{ $departamento->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('departamentos.actualizarcampos', $departamento) }}">
			@csrf
			@method('PUT')
			
			<div class="row">
				@foreach ($campos as $campo)
					<div class="col-md-12 col-sm-12 col-xs-12">
	                    <label>
	                        <input type="checkbox" name="campos[]" value="{{ $campo->id }}"
	                        {{ $departamento->TieneCampo($campo->id) ? "checked" : "" }}> {{ $campo->nombre }}
	                    </label>
	                </div>
				@endforeach		
			</div>

			@if($errors->has('campos'))
		        <div class="alert alert-danger">
		            <ul>
		                <strong>{{ $errors->first('campos') }}</strong>
		            </ul>
		        </div>
		    @endif

		    <div class="row">
		    	<div class="col-md-4">
					<button type="submit" id="btnActualizarCampos" class="btn btn-lg btn-success pull-right">Actualizar campos</button>
		    	</div>
		    </div>
		</form>
	</div
	
@endsection
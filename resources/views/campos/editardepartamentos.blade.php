@extends('layouts.app')

@section('content')
	<h2>Departamentos que tiene el campo {{ $campo->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('campos.actualizardepartamentos', $campo) }}">
			@csrf
			@method('PUT')
			
			<div class="row">
				@foreach ($departamentos as $departamento)
					<div class="col-md-12 col-sm-12 col-xs-12">
	                    <label>
	                        <input type="checkbox" name="departamentos[]" value="{{ $departamento->id }}" 
	                        	{{ $campo->TieneDepartamento($departamento->id) ? "checked" : "" }}> {{ $departamento->nombre }}
	                    </label>
	                </div>
				@endforeach		

			</div>

			@if($errors->has('departamentos'))
		        <div class="alert alert-danger">
		            <ul>
		                <strong>{{ $errors->first('departamentos') }}</strong>
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
@extends('layouts.app')

@section('content')
	<h2>Crear nuevo departamento</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('departamentos.crear') }}">
			<div class="col-md6 col-sm-6 col-xs-12">
				@csrf
				
				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
	                <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
	                <div class="col-md-10">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ old('nombre') }}" autofocus>

	                    @if ($errors->has('nombre'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('nombre') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <button type="submit" id="btnCrearCampo" class="btn btn-lg btn-success pull-right">Crear departamento</button>
	        </div>
		</form>
	</div>
@endsection
@extends('layouts.app')

@section('content')
	<h2>Edición del formato {{ $formato->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('formatos.actualizar', $formato) }}">
			<div class="col-md6 col-sm-6 col-xs-12">
				@csrf
				@method('PUT')
				
				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
	                <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
	                <div class="col-md-10">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ $formato->nombre }}">

	                    @if ($errors->has('nombre'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('nombre') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <button type="submit" id="btnCrearFormato" class="btn btn-lg btn-success pull-right">Actualizar formato</button>
	        </div>
		</form>
	</div>
@endsection
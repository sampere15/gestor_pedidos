@extends('layouts.app')

@section('content')
	<h2>Edición de la socidedad {{ $sociedad->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('sociedades.actualizar', $sociedad) }}">
			<div class="col-md6 col-sm-6 col-xs-12">
				@csrf
				@method('PUT')
				
				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
	                <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
	                <div class="col-md-10">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ $sociedad->nombre }}">

	                    @if ($errors->has('nombre'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('nombre') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('cif') ? ' has-error' : '' }}">
	                <label for="inputCIF" class="col-md-2 control-label">CIF</label>
	                <div class="col-md-10">
	                    <input id="inputCIF" type="text" class="form-control" name="cif" placeholder="CIF" required value="{{ $sociedad->cif }}">

	                    @if ($errors->has('cif'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('cif') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <button type="submit" id="btnCrearSociedad" class="btn btn-lg btn-success pull-right">Actualizar socidedad</button>
	        </div>
		</form>
	</div>
@endsection
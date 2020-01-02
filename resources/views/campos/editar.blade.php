@extends('layouts.app')

@section('content')

	<h2>Editando el campo {{ $campo->nombre }}</h2>
	
	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('campos.actualizar', $campo) }}">
			<div class="col-md6 col-sm-6 col-xs-12">
				@csrf
				@method('PUT')
				
				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
	                <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
	                <div class="col-md-10">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ $campo->nombre }}">

	                    @if ($errors->has('nombre'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('nombre') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('abreviatura') ? ' has-error' : '' }}">
	                <label for="inputAbreviatura" class="col-md-2 control-label">Abreviatura</label>
	                <div class="col-md-10">
	                    <input id="inputAbreviatura" type="text" class="form-control" name="abreviatura" placeholder="Abreviatura" required value="{{ $campo->abreviatura }}">

	                    @if ($errors->has('abreviatura'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('abreviatura') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="selectSociedad" class="col-sm-2 control-label">Sociedad favorita</label>
	                <div class="col-sm-10">
	            	    <select class="form-control" name="selectSociedad">
	            	    	@foreach ($sociedades as $sociedad)
	            	    		<option value="{{ $sociedad->id }}" {{ $campo->sociedadFavorita->id == $sociedad->id ? "checked" : "" }}>{{ $sociedad->nombre }}</option>
	            	    	@endforeach
	            	    </select>
	                </div>
	            </div>

	            <button type="submit" id="btnCrearCampo" class="btn btn-lg btn-success pull-right">Actualizar datos</button>
	        </div>
		</form>
	</div>
@endsection
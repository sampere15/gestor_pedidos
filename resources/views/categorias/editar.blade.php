@extends('layouts.app')

@section('content')
	<h2>Edición de la categoría {{ $categoria->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('categorias.actualizar', $categoria) }}">
			<div class="col-md6 col-sm-6 col-xs-12">
				@csrf
				@method('PUT')
				
				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
	                <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
	                <div class="col-md-10">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ $categoria->nombre }}">

	                    @if ($errors->has('nombre'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('nombre') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <button type="submit" id="btnCrearCategoria" class="btn btn-lg btn-success pull-right">Actualizar categoría</button>
	        </div>
		</form>
	</div>
@endsection
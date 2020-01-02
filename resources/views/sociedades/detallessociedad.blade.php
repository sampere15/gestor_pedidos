@extends('layouts.app')

@section('content')

	<h2>Detalles de la sociedad {{ $sociedad->nombre }}</h2>

	@if(Auth::user()->can('sociedades.listar') || Auth::user()->can('sociedades.editar'))
		<div class="row" style="margin-bottom: 20px">
			@can('sociedades.listar')	
				<div class="pull-left">
					<a href="{{ route('sociedades.listar') }}" class="btn btn-lg btn-default">
						<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver al listado de sociedades
					</a>
				</div>
			@endcan
			@can('sociedades.editar')
				<div class="pull-right">
					<a href="{{ route('sociedades.editar', $sociedad) }}" class="btn btn-lg btn-warning">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar
					</a>
				</div>
			@endcan
		</div>
	@endif

	<div class="row">
		<form class="form-horizontal">
			<div class="col-md6 col-sm-6 col-xs-12">
				
				<div class="form-group">
	                <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
	                <div class="col-md-10">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" value="{{ $sociedad->nombre }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputCIF" class="col-md-2 control-label">CIF</label>
	                <div class="col-md-10">
	                    <input id="inputCIF" type="text" class="form-control" name="cif" value="{{ $sociedad->cif }}" readonly>
	                </div>
	            </div>

	        </div>
		</form>

	</div>
	<hr>
	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-12">
			<h3>Campos que gestiona</h3>

			<div class="row">
				@can('sociedades.editar')
					<a href="{{ route('sociedades.editarcampos', $sociedad) }}" class="btn btn-sm btn-primary">Editar campos</a>
				@endcan
			</div>

			<div class="row" style="margin-top: 20px">
				<table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 60%">
					@foreach ($sociedad->campos as $campo)
						<tr>
							<td>
								@can('campos.verdetalles')
									<a href="{{ route('campos.verdetalles', $campo) }}" style="text-decoration: none">{{ $campo->nombre }}</a>
								@else
									{{ $campo->nombre }}
								@endcan
							</td>
						</tr>
					@endforeach
				</table>
			</div>
		</div>
	</div>

@endsection
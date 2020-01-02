@extends('layouts.app')

@section('content')

	<h2>Detalles del campo {{ $campo->nombre }}</h2>

	@if(Auth::user()->can('campos.listar') || Auth::user()->can('campos.editar'))
		<div class="row" style="margin-bottom: 20px">
			@can('campos.listar')
				<div class="pull-left">
					<a href="{{ route('campos.listar') }}" class="btn btn-lg btn-default">
						<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver al listado de campos
					</a>
				</div>
			@endcan
			@can('campos.editar')
				<div class="pull-right">
					<a href="{{ route('campos.editar', $campo) }}" class="btn btn-lg btn-warning">
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
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" value="{{ $campo->nombre }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputAbreviatura" class="col-md-2 control-label">Abreviatura</label>
	                <div class="col-md-10">
	                    <input id="inputAbreviatura" type="text" class="form-control" name="abreviatura" value="{{ $campo->abreviatura }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputSociedadFavorita" class="col-md-2 control-label">Sociedad Favorita</label>
	                <div class="col-md-10">
	                    <input id="inputSociedadFavorita" type="text" class="form-control" name="nombre" value="{{ $campo->sociedadFavorita->nombre }}" readonly>
	                </div>
	            </div>

	        </div>
		</form>

	</div>
	<hr>
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-12">
			<h3>Departamentos</h3>
			<div class="row">
				{{-- <div class="pull-left">
					@can('departamentos.crear')
						<a href="{{ route('departamentos.crear') }}" class="btn btn-sm btn-primary">Nuevo departamento</a>
					@endcan
				</div> --}}
				<div class="pull-right" style="margin-right: 40px">
					@can('campos.editardepartamentos')
						<a href="{{ route('campos.editardepartamentos', $campo) }}" class="btn btn-sm btn-warning">Editar</a>
					@endcan
				</div>
			</div>
			<div class="row" style="margin-top:20px">
				<table id="tablaListadoDepartamentos" class="table table-condensed table-striped"  style="width: 90%">
					@foreach ($campo->departamentos as $departamento)
						<tr>
							<td>
								<a href="{{ route('departamentos.verdetalles', $departamento->id) }}">{{ $departamento->nombre }}</a>
							</td>
						</tr>
					@endforeach
				</table>
			</div>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12">
			<h3>Direcciones</h3>
			<div class="row">
				@can('direcciones.crear')
					<a href="{{ route('direcciones.crear', $campo) }}" class="btn btn-sm btn-primary">Nueva dirección</a>
				@endcan
			</div>
			<div class="row" style="margin-top:20px">
				<table id="tablaListadoCampos" class="table table-condensed table-striped"  style="width: 90%">
					@foreach ($campo->direcciones->where('activo') as $direccion)
						<tr>
							<td><a href="{{ route('direcciones.verdetalles', $direccion->id) }}">{{ $direccion->nombre }}</a></td>
							@can('direcciones.verdetalles')
								<td width="10px">
									<a class="btn btn-sm btn-default" href="{{ route('direcciones.verdetalles', $direccion) }}">Ver detalles</span></a>
								</td>
							@endcan
						</tr>
					@endforeach
				</table>
			</div>
		</div>
		{{-- <div class="col-md-3 col-sm-3 col-xs-12">
			<h3>Empleados</h3>
			<div class="row">
				@can('usuarios.crear')
					<a href="" class="btn btn-sm btn-primary">Nuevo empleado</a>
				@endcan
			</div>
			<div class="row" style="margin-top:20px">
				<table id="tablaListadoEmpleados" class="table table-condensed table-striped"  style="width: 90%">
					@foreach ($campo->usuarios->where('activo') as $usuario)
						<tr>
							<td>
								@can('usuarios.verdetalles')
									<a href="{{ route('usuarios.verdetalles', $usuario) }}" style="text-decoration: none">{{ $usuario->nombre }} {{ $usuario->apellidos }}</a>
								@else
									{{ $usuario->nombre }} {{ $usuario->apellidos }}
								@endcan
							</td>
						</tr>
					@endforeach
				</table>
			</div>
		</div> --}}
		<div class="col-md-3 col-sm-3 col-xs-12">
			<h3>Sociedades</h3>
			<div class="row">
				{{-- <div class="pull-left">
					@can('sociedades.crear')
						<a href="" class="btn btn-sm btn-primary">Nueva sociedad</a>
					@endcan
				</div> --}}
				<div class="pull-right" style="margin-right: 40px">
					@can('campos.editarsociedades')
						<a href="{{ route('campos.editarsociedades', $campo) }}" class="btn btn-sm btn-warning">Editar</a>
					@endcan
				</div>
			</div>
			<div class="row" style="margin-top:20px">
				<table id="tablaListadoEmpleados" class="table table-condensed table-striped"  style="width: 90%">
					@foreach ($campo->sociedades->where('activo') as $sociedad)
						<tr>
							<td>
								@can('sociedades.verdetalles')
									<a href="{{ route('sociedades.verdetalles', $sociedad) }}" style="text-decoration: none">{{ $sociedad->nombre }}</a>
								@else
									{{ $sociedad->nombre }}
								@endcan
							</td>
						</tr>
					@endforeach
				</table>
			</div>
		</div>
	</div>

@endsection

@push('footscripts')
	<script type="text/javascript">

		//	Envía el formulario para poder eliminar la direccion
		function BorrarDireccion()
		{
			$('#formularioBorrarDireccion').submit();

		}
	</script>
@endpush

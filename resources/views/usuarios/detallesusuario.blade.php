@extends('layouts.app')

@section('content')
	<h2>Datos del usuario: {{ $usuario->nombre }} {{ $usuario->apellidos }}</h2>

	@if(Auth::user()->can('usuarios.listar') || Auth::user()->can('usuarios.editar'))
		<div class="row" style="margin-bottom: 20px">
			@can('usuarios.listar')
				<div class="pull-left">
					<a href="{{ route('usuarios.listar') }}" class="btn btn-lg btn-default">
						<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver al listado de usuarios
					</a>
				</div>
			@endcan
			<div class="pull-right">
				@can('usuarios.listarpedidos')
					<a href="{{ route('usuarios.listarpedidos', $usuario) }}" class="btn btn-lg btn-default">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Ver pedidos realizados por el usuario
					</a>
				@endcan
				@can('usuarios.editar')
					<a href="{{ route('usuarios.editar', $usuario) }}" class="btn btn-lg btn-warning">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar
					</a>
				@endcan
				@can('usuarios.borrar')
					<button href="" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#modalBorrarUsuario">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar
					</button>
				@endcan
			</div>
		</div>
	@endif

	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-12 form-horizontal">

			<div class="form-group">
	            <label for="inputNombre" class="col-md-4 control-label">Nombre</label>
	            <div class="col-md-8">
	                <input id="inputNombre" type="text" class="form-control" name="nombre" value="{{ $usuario->nombre }}" readonly>
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="inputApellidos" class="col-md-4 control-label">Apellidos</label>
	            <div class="col-md-8">
	                <input id="inputApellidos" type="text" class="form-control" name="apellidos" value="{{ $usuario->apellidos }}" readonly>
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="inputNIF" class="col-md-4 control-label">NIF</label>
	            <div class="col-md-8">
	                <input id="inputNIF" type="text" class="form-control" name="nif" value="{{ $usuario->nif }}" readonly>
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="inputEmail" class="col-md-4 control-label">Email</label>
	            <div class="col-md-8">
	                <input id="inputEmail" type="text" class="form-control" name="email" value="{{ $usuario->email }}" readonly>
	            </div>
	        </div>

	    </div>
	</div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<h3>Departamentos en los que puede realizar pedidos</h3>
			@can('usuarios.editardepartamentoscampos')
				<a href="{{ route('usuarios.editardepartamentoscampos', $usuario) }}" class="btn btn-warning">Editar</a>
			@endcan

			<table id="tablaListadoUsuarios" class="table table-condensed table-striped"  style="width: 100%">
				<thead>
					<tr>
						<th>Departamento</th>
						<th>Campos</th>
					</tr>
				</thead>
				<tbody>
					@foreach($departamentos as $departamento)
						<tr>
							<td><a href="{{ route('departamentos.verdetalles', $departamento->id) }}">{{ $departamento->nombre }}</a></td>
							<td>
								@for ($i = 0; $i < count($array_departamentos_campos[$departamento->id]); $i++)
									<a href="{{ route('campos.verdetalles', $array_departamentos_campos[$departamento->id][$i]->id) }}">{{ $array_departamentos_campos[$departamento->id][$i]->nombre }}</a>
									@if ($i < count($array_departamentos_campos[$departamento->id]) -1)
										, 
									@endif
								@endfor
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>


	{{-- <div class="row">
		<form>
			<div class="col-md-6 col-sm-12 col-xs-12" style="border: 1px solid #ccc; border-radius: 16px; padding-top: 20px; padding-bottom: 20px">

				<div class="form-group">
		            <label for="inputNombre" class="col-md-2 control-label">Nombre</label>
		            <div class="col-md-10">
		                <input id="inputNombre" type="text" class="form-control" name="nombre" value="{{ $usuario->nombre }}" readonly>
		            </div>
		        </div>

				<div class="form-group">
		            <label for="inputApellidos" class="col-md-2 control-label">Apellidos</label>
		            <div class="col-md-10">
		                <input id="inputApellidos" type="text" class="form-control" name="apellidos" value="{{ $usuario->apellidos }}" readonly>
		            </div>
		        </div>

				<div class="form-group">
		            <label for="inputNIF" class="col-md-2 control-label">NIF</label>
		            <div class="col-md-10">
		                <input id="inputNIF" type="text" class="form-control" name="nif" value="{{ $usuario->nif }}" readonly>
		            </div>
		        </div>

				<div class="form-group">
		            <label for="inputEmail" class="col-md-2 control-label">Email</label>
		            <div class="col-md-10">
		                <input id="inputEmail" type="text" class="form-control" name="email" value="{{ $usuario->email }}" readonly>
		            </div>
		        </div>

				<div class="form-group">
		            <label for="inputCampo" class="col-md-2 control-label">Campo</label>
		            <div class="col-md-10">
		                <input id="inputCampo" type="text" class="form-control" name="campo" value="{{ $usuario->campo->nombre }}" readonly>
		            </div>
		        </div>
			</div>
		</form>
	</div> --}}

	@can('usuarios.borrar')
        {{-- Modal para confirmar el borrado de una direccion --}}
        <div class="modal fade" id="modalBorrarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalBorrarUsuario">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">¿Seguro que quiere eliminar este usuario?</h3>
                    </div>
                    {{-- <div class="modal-body">
                        <h4>Puede indicar a continuación los motivos de cancelación si lo desea:</h4>
                        <textarea class="form-control" rows="5" id="taMotivoCancelacion" placeholder="Indique aquí los motivos de la cancelación" autofocus></textarea>
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            No, no borrar
                        </button>
                        <button type="button" onclick="$('#formularioBorrarUsuario').submit()" class="btn btn-danger">
                            Sí, confirmar el borrado
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" id="formularioBorrarUsuario" action="{{ route('usuarios.borrar', $usuario) }}">
            @csrf
            @method('DELETE')
        </form>
    @endcan
@endsection
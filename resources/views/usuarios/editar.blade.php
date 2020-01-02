@extends('layouts.app')

@section('content')
	<h3>Edición del usuario: {{ $usuario->apellidos }}, {{ $usuario->nombre }}</h3>

	@include('partials.errores')

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('usuarios.actualizar', $usuario) }}">
			<div class="col-md-6 col-sm-12 col-xs-12" style="border: 1px solid #ccc; border-radius: 16px; padding-top: 20px; padding-bottom: 20px">
				@csrf
				@method('PUT')

				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
		            <label for="inputNombre" class="col-md-2 control-label">Nombre</label>

		            <div class="col-md-10">
		                <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ $usuario->nombre }}">

		                @if ($errors->has('nombre'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('nombre') }}</strong>
		                    </span>
		                @endif
		            </div>
		        </div>

				<div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
		            <label for="inputApellidos" class="col-md-2 control-label">Apellidos</label>

		            <div class="col-md-10">
		                <input id="inputApellidos" type="text" class="form-control" name="apellidos" placeholder="Apellidos" required value="{{ $usuario->apellidos }}">

		                @if ($errors->has('apellidos'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('apellidos') }}</strong>
		                    </span>
		                @endif
		            </div>
		        </div>

				<div class="form-group{{ $errors->has('nif') ? ' has-error' : '' }}">
		            <label for="inputNIF" class="col-md-2 control-label">NIF</label>

		            <div class="col-md-10">
		                <input id="inputNIF" type="text" maxlength="9" class="form-control" name="nif" placeholder="NIF" required value="{{ $usuario->nif }}">

		                @if ($errors->has('nif'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('nif') }}</strong>
		                    </span>
		                @endif
		            </div>
		        </div>

				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		            <label for="inputEmail" class="col-md-2 control-label">Email</label>

		            <div class="col-md-10">
		                <input id="inputEmail" type="email" class="form-control" name="email" placeholder="Correo electrónico" required value="{{ $usuario->email }}">

		                @if ($errors->has('email'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('email') }}</strong>
		                    </span>
		                @endif
		            </div>
		        </div>

				{{-- <div class="form-group">
				    <label for="selectCampo" class="col-sm-2 control-label">Campo</label>
				    <div class="col-sm-10">
					    <select class="form-control" name="selectCampo">
					    	@foreach ($campos as $campo)
					    		<option value="{{ $campo->id }}" {{ ($usuario->campo->id == $campo->id) ? "selected" : "" }}>{{ $campo->nombre }}</option>
					    	@endforeach
					    </select>
				    </div>
				</div> --}}
				@can('usuarios.editarpermisos')
	            @else
					<button id="btnActualizarUsuario" class="btn btn-success pull-right">Actualizar datos usuario</button>
				@endcan
			</div>

			@can('usuarios.editarpermisos')
                <div class="row col-md-12 col-sm-12 col-xs-12">
                    @foreach ($categoriasPermisos as $categoria)
                        <hr style="border-style: inset; border-width: 1px;border-color: #39bfc9">
                        <h4 style="color: #39bfc9">Permisos sobre {{ $categoria }}</h4>
                        <div class="row">
                            @foreach ($permisos as $permiso)
                                @if(strcasecmp($categoria, substr($permiso->slug, 0, stripos($permiso->slug, "."))) == 0)
                                    <div class="col-md-3 col-sm-3 col-xs-2">
                                        <label>
                                            <input type="checkbox" name="permisos[]" value="{{ $permiso->id }}" {{ $usuario->can($permiso->slug) ? "checked" : "" }}> {{ $permiso->name }}
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
			    <button type="submit" id="btnCrearUsuario" class="btn btn-lg btn-success pull-right">Actualizar datos usuario</button>
            @endcan
		</form>
	</div>

@endsection
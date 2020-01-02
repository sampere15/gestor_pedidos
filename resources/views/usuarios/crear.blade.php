@extends('layouts.app')

@section('content')
    <h2>Crear un nuevo usuario</h2>
    
    @include('partials.errores')

	<div class="row">
        <form class="form-horizontal" method="POST" action="{{ route('usuarios.generar') }}">
            <div class="col-md-6 col-sm-12 col-xs-12" style="border: 1px solid #ccc; border-radius: 16px; padding-top: 20px; padding-bottom:20px">
    			@csrf

    			<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label for="inputNombre" class="col-md-2 control-label">Nombre</label>

                    <div class="col-md-10">
                        <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ old('nombre') }}">

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
                        <input id="inputApellidos" type="text" class="form-control" name="apellidos" placeholder="Apellidos" required value="{{ old('apellidos') }}">

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
                        <input id="inputNIF" type="text" maxlength="9" class="form-control" name="nif" placeholder="NIF" required value="{{ old('nif') }}">

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
                        <input id="inputEmail" type="email" class="form-control" name="email" placeholder="Correo electrónico" required value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                @can('usuarios.editarpermisos')
                @else
                    <button type="submit" id="btnCrearUsuario" class="btn btn-lg btn-success pull-right">Generar usuario</button>
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
                                            <input type="checkbox" name="permisos[]" value="{{ $permiso->id }}"> {{ $permiso->name }}
                                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" title="{{ $permiso->description }}"></span>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
			    <button type="submit" id="btnCrearUsuario" class="btn btn-lg btn-success pull-right">Generar usuario</button>
            @endcan
        </form>
	</div>

    <!-- Modal informacion -->
    {{-- <div id="informacionModal" class="modal fade modal-en-medio">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalTitle" class="modal-title">Generando usuario...</h4>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- Fin del modal informacion --}}
@endsection
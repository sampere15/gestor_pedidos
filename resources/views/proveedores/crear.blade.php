@extends('layouts.app')

@section('content')

	<h2>Creación de nuevo proveedor</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('proveedores.guardar') }}">
			<div class="col-md6 col-sm-6 col-xs-12">
				@csrf
				
				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
	                <label for="inputNombre" class="col-md-3 control-label">*Nombre</label>
	                <div class="col-md-9">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ old('nombre') }}">

	                    @if ($errors->has('nombre'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('nombre') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('cif') ? ' has-error' : '' }}">
	                <label for="inputCIF" class="col-md-3 control-label">CIF</label>
	                <div class="col-md-9">
	                    <input id="inputCIF" type="text" class="form-control" name="cif" placeholder="CIF" value="{{ old('cif') }}">

	                    @if ($errors->has('cif'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('cif') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
	                <label for="inputDireccion" class="col-md-3 control-label">Dirección</label>
	                <div class="col-md-9">
	                    <input id="inputDireccion" type="text" class="form-control" name="direccion" placeholder="Dirección" value="{{ old('direccion') }}">

	                    @if ($errors->has('direccion'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('direccion') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('provincia') ? ' has-error' : '' }}">
	                <label for="inputProvincia" class="col-md-3 control-label">Provincia</label>
	                <div class="col-md-9">
	                    <input id="inputProvincia" type="text" class="form-control" name="provincia" placeholder="Provincia" value="{{ old('provincia') }}">

	                    @if ($errors->has('provincia'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('provincia') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('pais') ? ' has-error' : '' }}">
	                <label for="inputPais" class="col-md-3 control-label">País</label>
	                <div class="col-md-9">
	                    <input id="inputPais" type="text" class="form-control" name="pais" placeholder="País" value="{{ old('pais') }}">

	                    @if ($errors->has('pais'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('pais') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('persona_contacto') ? ' has-error' : '' }}">
	                <label for="inputPeronsaContacto" class="col-md-3 control-label">Persona Contacto</label>
	                <div class="col-md-9">
	                    <input id="inputPersonaContacto" type="text" class="form-control" name="persona_contacto" placeholder="Persona Contacto" value="{{ old('persona_contacto') }}">

	                    @if ($errors->has('persona_contacto'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('persona_contacto') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('telefono_contacto') ? ' has-error' : '' }}">
	                <label for="inputTelefonoContacto" class="col-md-3 control-label">*Teléfono Contacto</label>
	                <div class="col-md-9">
	                    <input id="inputTelefonoContacto" type="text" class="form-control" name="telefono_contacto" placeholder="Teléfono Contacto" value="{{ old('telefono_contacto') }}" required>

	                    @if ($errors->has('telefono_contacto'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('telefono_contacto') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('correo_contacto') ? ' has-error' : '' }}">
	                <label for="inputCorreoContacto" class="col-md-3 control-label">*Correo Contacto</label>
	                <div class="col-md-9">
	                    <input id="inputCorreoContacto" type="email" class="form-control" name="correo_contacto" placeholder="Correo Contacto" value="{{ old('correo_contacto') }}" required>

	                    @if ($errors->has('correo_contacto'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('correo_contacto') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <p style="color: red">*Los campos marcados son obligatorios</p>

	            <button type="submit" id="btnCrearCampo" class="btn btn-lg btn-success pull-right">Crear proveedor</button>
	        </div>
		</form>
	</div>

@endsection
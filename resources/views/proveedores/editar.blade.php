@extends('layouts.app')

@section('content')
	<h2>Detalles del proveedor {{ $proveedor->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('proveedores.actualizar', $proveedor) }}">
			<div class="col-md6 col-sm-6 col-xs-12">
				@csrf
				@method('PUT')
				
				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
	                <label for="inputNombre" class="col-md-3 control-label">Nombre</label>
	                <div class="col-md-9">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" value="{{ $proveedor->nombre }}" required >

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
	                    <input id="inputCIF" type="text" class="form-control" name="cif" value="{{ $proveedor->cif }}">

	                    @if ($errors->has('cif'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('cif') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
	                <label for="inputDireccion" class="col-md-3 control-label">Direccion</label>
	                <div class="col-md-9">
	                    <input id="inputDireccion" type="text" class="form-control" name="direccion" value="{{ $proveedor->direccion }}">

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
	                    <input id="inputProvincia" type="text" class="form-control" name="provincia" value="{{ $proveedor->provincia }}">

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
	                    <input id="inputPais" type="text" class="form-control" name="pais" value="{{ $proveedor->pais }}">

	                    @if ($errors->has('pais'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('pais') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('persona_contacto') ? ' has-error' : '' }}">
	                <label for="inputPeronsaContacto" class="col-md-3 control-label">Peronsa Contacto</label>
	                <div class="col-md-9">
	                    <input id="inputPeronsaContacto" type="text" class="form-control" name="persona_contacto" value="{{ $proveedor->persona_contacto }}">

	                    @if ($errors->has('persona_contacto'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('persona_contacto') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('telefono_contacto') ? ' has-error' : '' }}">
	                <label for="inputTelefonoContacto" class="col-md-3 control-label">Telefono Contacto</label>
	                <div class="col-md-9">
	                    <input id="inputTelefonoContacto" type="text" class="form-control" name="telefono_contacto" value="{{ $proveedor->telefono_contacto }}">

	                    @if ($errors->has('telefono_contacto'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('telefono_contacto') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('correo_contacto') ? ' has-error' : '' }}">
	                <label for="inputCorreoContacto" class="col-md-3 control-label">Correo Contacto</label>
	                <div class="col-md-9">
	                    <input id="inputCorreoContacto" type="email" class="form-control" name="correo_contacto" value="{{ $proveedor->correo_contacto }}">

	                    @if ($errors->has('correo_contacto'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('correo_contacto') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <button type="submit" id="btnCrearCampo" class="btn btn-lg btn-success pull-right">Actualizar proveedor</button>
	        </div>
		</form>
	</div>
@endsection
@extends('layouts.app')

@section('content')
	<h2>Edición de dirección del campo {{ $direccion->campo->nombre }}</h2>

	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('direcciones.actualizar', $direccion) }}">
			<div class="col-md6 col-sm-6 col-xs-12">
				@csrf
				@method('PUT')
				
				<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
	                <label for="inputNombre" class="col-md-3 control-label">Nombre</label>
	                <div class="col-md-9">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required value="{{ $direccion->nombre }}">

	                    @if ($errors->has('nombre'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('nombre') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

				<div class="form-group{{ $errors->has('calle') ? ' has-error' : '' }}">
	                <label for="inputCalle" class="col-md-3 control-label">Calle</label>
	                <div class="col-md-9">
	                    <input id="inputCalle" size="255" type="text" class="form-control" name="calle" placeholder="Calle" required value="{{ $direccion->calle }}">

	                    @if ($errors->has('calle'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('calle') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('ciudad') ? ' has-error' : '' }}">
	                <label for="inputCiudad" class="col-md-3 control-label">Ciudad</label>
	                <div class="col-md-9">
	                    <input id="inputCiudad" size="255" type="text" class="form-control" name="ciudad" placeholder="Ciudad" required value="{{ $direccion->ciudad }}">

	                    @if ($errors->has('ciudad'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('ciudad') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('codigo_postal') ? ' has-error' : '' }}">
	                <label for="inputCodigoPostal" class="col-md-3 control-label">CodigoPostal</label>
	                <div class="col-md-9">
	                    <input id="inputCodigoPostal" size="255" type="text" class="form-control" name="codigo_postal" placeholder="Codigo Postal" required value="{{ $direccion->codigo_postal }}">

	                    @if ($errors->has('codigo_postal'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('codigo_postal') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('provincia') ? ' has-error' : '' }}">
	                <label for="inputProvincia" class="col-md-3 control-label">Provincia</label>
	                <div class="col-md-9">
	                    <input id="inputProvincia" size="255" type="text" class="form-control" name="provincia" placeholder="Provincia" required value="{{ $direccion->provincia }}">

	                    @if ($errors->has('provincia'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('provincia') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('pais') ? ' has-error' : '' }}">
	                <label for="inputPais" class="col-md-3 control-label">Pais</label>
	                <div class="col-md-9">
	                    <input id="inputPais" size="255" type="text" class="form-control" name="pais" placeholder="Pais" required value="{{ $direccion->pais }}">

	                    @if ($errors->has('pais'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('pais') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('persona_contacto') ? ' has-error' : '' }}">
	                <label for="inputPersonaContacto" class="col-md-3 control-label">Persona Contacto</label>
	                <div class="col-md-9">
	                    <input id="inputPersonaContacto" size="255" type="text" class="form-control" name="persona_contacto" placeholder="Persona Contacto" required value="{{ $direccion->persona_contacto }}">

	                    @if ($errors->has('persona_contacto'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('persona_contacto') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group{{ $errors->has('numero_contacto') ? ' has-error' : '' }}">
	                <label for="inputNumeroContacto" class="col-md-3 control-label">Numero Contacto</label>
	                <div class="col-md-9">
	                    <input id="inputNumeroContacto" size="255" type="text" class="form-control" name="numero_contacto" placeholder="Numero Contacto" required value="{{ $direccion->numero_contacto }}">

	                    @if ($errors->has('numero_contacto'))
	                        <span class="help-block">
	                            <strong>{{ $errors->first('numero_contacto') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="selectCampo" class="col-sm-3 control-label">Campo</label>
	                <div class="col-sm-9">
	            	    <select class="form-control" name="selectCampo">
	            	    	@foreach ($campos as $campo)
	            	    		<option value="{{ $campo->id }}" {{ $campo->id == $direccion->campo_id ? "selected" : "" }}>{{ $campo->nombre }}</option>
	            	    	@endforeach
	            	    </select>
	                </div>
	            </div>

	            <button type="submit" id="btnCrearDireccion" class="btn btn-lg btn-success pull-right">Actualizar dirección</button>
	        </div>
		</form>
	</div>
@endsection
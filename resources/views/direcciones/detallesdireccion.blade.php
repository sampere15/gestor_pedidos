@extends('layouts.app')

@section('content')
	<h2>Detalles de la direccion {{ $direccion->nombre }}</h2>

	@if(Auth::user()->can('direcciones.editar') || Auth::user()->can('direcciones.borrar'))
		<div class="row" style="margin-bottom: 20px">
            @can('campos.verdetalles')
                <div class="pull-left">
                    <a href="{{ route('campos.verdetalles', $direccion->campo) }}" class="btn btn-lg btn-default">
                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver a los detalles del campo
                    </a>
                </div>
            @endcan
			<div class="pull-right">
				@can('direcciones.editar')
					<a href="{{ route('direcciones.editar', $direccion) }}" class="btn btn-lg btn-warning">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar
					</a>
				@endcan
				@can('direcciones.borrar')
					<button href="" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#modalBorrarDireccion">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar
					</button>
				@endcan
			</div>
		</div>
	@endif

	<div class="row">
		<form class="form-horizontal">
			<div class="col-md6 col-sm-6 col-xs-12">
				
                <label for="inputNombre" class="col-md-4 col-sm-4 control-label">Nombre</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputNombre" type="text" class="form-control" value="{{ $direccion->nombre }}" readonly>
                </div>

                <label for="inputCalle" class="col-md-4 col-sm-4 control-label">Calle</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputCalle" size="255" type="text" class="form-control" value="{{ $direccion->calle }}" readonly>
                </div>

                <label for="inputCiudad" class="col-md-4 col-sm-4 control-label">Ciudad</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputCiudad" size="255" type="text" class="form-control" value="{{ $direccion->ciudad }}" readonly>
                </div>

                <label for="inputCodigoPostal" class="col-md-4 col-sm-4 control-label">CodigoPostal</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputCodigoPostal" size="255" type="text" class="form-control"Postal" value="{{ $direccion->codigo_postal }}" readonly>
                </div>

                <label for="inputProvincia" class="col-md-4 col-sm-4 control-label">Provincia</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputProvincia" size="255" type="text" class="form-control" value="{{ $direccion->provincia }}" readonly>
                </div>

                <label for="inputPais" class="col-md-4 col-sm-4 control-label">Pais</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputPais" size="255" type="text" class="form-control" value="{{ $direccion->pais }}" readonly>
                </div>

                <label for="inputPersonaContacto" class="col-md-4 col-sm-4 control-label">Persona Contacto</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputPersonaContacto" size="255" type="text" class="form-control" value="{{ $direccion->persona_contacto }}" readonly>
                </div>

                <label for="inputNumeroContacto" class="col-md-4 col-sm-4 control-label">Numero Contacto</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputNumeroContacto" size="255" type="text" class="form-control" value="{{ $direccion->numero_contacto }}" readonly>
                </div>

	            <label for="inputCampo" class="col-md-4 col-sm-4 control-label">Campos</label>
                <div class="col-md-8 col-sm-8">
                    <input id="inputCampo" size="255" type="text" class="form-control" value="{{ $direccion->campo->nombre }}" readonly>
                </div>
	        </div>
		</form>
	</div>

	@can('direcciones.borrar')
        {{-- Modal para confirmar el borrado de una direccion --}}
        <div class="modal fade" id="modalBorrarDireccion" tabindex="-1" role="dialog" aria-labelledby="modalBorrarDireccion">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">¿Seguro que quiere eliminar esta dirección?</h3>
                    </div>
                    {{-- <div class="modal-body">
                        <h4>Puede indicar a continuación los motivos de cancelación si lo desea:</h4>
                        <textarea class="form-control" rows="5" id="taMotivoCancelacion" placeholder="Indique aquí los motivos de la cancelación" autofocus></textarea>
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            No, no borrar
                        </button>
                        <button type="button" onclick="$('#formularioBorrarDireccion').submit()" class="btn btn-danger">
                            Sí, confirmar el borrado
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" id="formularioBorrarDireccion" action="{{ route('direcciones.borrar', $direccion) }}">
            @csrf
            @method('DELETE')
        </form>
    @endcan
@endsection
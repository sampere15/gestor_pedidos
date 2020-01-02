@extends('layouts.app')

@section('content')
	<h2>Detalles proveedor {{ $proveedor->nombre }}</h2>

	@if(Auth::user()->can('proveedores.listar') || Auth::user()->can('proveedores.editar') || Auth::user()->can('proveedores.listar'))
		<div class="row" style="margin-bottom: 20px">
			@can('proveedores.listar')
				<div class="pull-left">
					<a href="{{ route('proveedores.listar') }}" class="btn btn-lg btn-default">
						<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver al listado de proveedores
					</a>
				</div>
			@endcan
			<div class="pull-right">
				@can('proveedores.listarpedidos')
					<a href="{{ route('proveedores.listarpedidos', $proveedor) }}" class="btn btn-lg btn-default">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Ver pedidos realizados al proveedor
					</a>
				@endcan
				@can('proveedores.editar')
					<a href="{{ route('proveedores.editar', $proveedor) }}" class="btn btn-lg btn-warning">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar
					</a>
				@endcan
				@can('proveedores.borrar')
					<button href="" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#modalBorrarProveedor">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar
					</button>
				@endcan
			</div>
		</div>
	@endif

	<div class="row">
		<div class="form-horizontal">
			<div class="col-md6 col-sm-6 col-xs-12">

				<div class="form-group">
	                <label for="inputNombre" class="col-md-3 control-label">Nombre</label>
	                <div class="col-md-9">
	                    <input id="inputNombre" type="text" class="form-control" name="nombre" value="{{ $proveedor->nombre }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputCIF" class="col-md-3 control-label">CIF</label>
	                <div class="col-md-9">
	                    <input id="inputCIF" type="text" class="form-control" name="nombre" value="{{ $proveedor->cif }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputDireccion" class="col-md-3 control-label">Direccion</label>
	                <div class="col-md-9">
	                    <input id="inputDireccion" type="text" class="form-control" name="nombre" value="{{ $proveedor->direccion }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputProvincia" class="col-md-3 control-label">Provincia</label>
	                <div class="col-md-9">
	                    <input id="inputProvincia" type="text" class="form-control" name="nombre" value="{{ $proveedor->provincia }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputPais" class="col-md-3 control-label">Pais</label>
	                <div class="col-md-9">
	                    <input id="inputPais" type="text" class="form-control" name="nombre" value="{{ $proveedor->pais }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputPersonaContacto" class="col-md-3 control-label">PersonaContacto</label>
	                <div class="col-md-9">
	                    <input id="inputPersonaContacto" type="text" class="form-control" name="nombre" value="{{ $proveedor->persona_contacto }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputTelefonoContacto" class="col-md-3 control-label">TelefonoContacto</label>
	                <div class="col-md-9">
	                    <input id="inputTelefonoContacto" type="text" class="form-control" name="nombre" value="{{ $proveedor->telefono_contacto }}" readonly>
	                </div>
	            </div>

	            <div class="form-group">
	                <label for="inputCorreoContacto" class="col-md-3 control-label">CorreoContacto</label>
	                <div class="col-md-9">
	                    <input id="inputCorreoContacto" type="text" class="form-control" name="nombre" value="{{ $proveedor->correo_contacto }}" readonly>
	                </div>
	            </div>

	        </div>
		</div>
	</div>

	@can('proveedores.borrar')
        {{-- Modal para confirmar el borrado de una direccion --}}
        <div class="modal fade" id="modalBorrarProveedor" tabindex="-1" role="dialog" aria-labelledby="modalBorrarProveedor">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">¿Seguro que quiere eliminar este proveedor?</h3>
                    </div>
                    {{-- <div class="modal-body">
                        <h4>Puede indicar a continuación los motivos de cancelación si lo desea:</h4>
                        <textarea class="form-control" rows="5" id="taMotivoCancelacion" placeholder="Indique aquí los motivos de la cancelación" autofocus></textarea>
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            No, no borrar
                        </button>
                        <button type="button" onclick="$('#formularioBorrarProveedor').submit()" class="btn btn-danger">
                            Sí, confirmar el borrado
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" id="formularioBorrarProveedor" action="{{ route('proveedores.borrar', $proveedor) }}">
            @csrf
            @method('DELETE')
        </form>
    @endcan
@endsection